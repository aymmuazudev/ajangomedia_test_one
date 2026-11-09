<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveUserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.create', [
            'roles' => User::roleOptionsFor($request->user()),
        ]);
    }

    public function store(SaveUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'role' => $request->validated('role'),
            'is_active' => $request->boolean('is_active'),
            'password' => Str::password(64),
        ]);

        Password::sendResetLink(['email' => $user->email]);
        ActivityLog::record($request->user(), 'user.created', "Created the account for {$user->name} as {$user->roleLabel()}.");

        return redirect()->route('admin.users.index')
            ->with('status', "Account created. A password setup link has been sent to {$user->email}.");
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => User::roleOptionsFor($request->user()),
        ]);
    }

    public function update(SaveUserRequest $request, User $user): RedirectResponse
    {
        $actor = $request->user();
        $data = [
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($actor->is($user) && ($data['role'] !== $user->role || ! $data['is_active'])) {
            return back()->withInput()
                ->withErrors(['role' => 'You cannot change your own role or deactivate your own account.']);
        }

        $blocked = DB::transaction(function () use ($user, $data) {
            User::query()
                ->where('role', User::ROLE_SUPER_ADMINISTRATOR)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get();

            if ($this->removesLastActiveSuperAdministrator($user, $data)) {
                return true;
            }

            $user->update($data);

            return false;
        });

        if ($blocked) {
            return back()->withInput()
                ->withErrors(['role' => 'At least one active Super Administrator must remain.']);
        }

        ActivityLog::record($actor, 'user.updated', "Updated the account for {$user->name}.");

        return redirect()->route('admin.users.index')->with('status', "Account for {$user->name} updated.");
    }

    public function sendPasswordReset(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        Password::sendResetLink(['email' => $user->email]);
        ActivityLog::record($request->user(), 'user.password_reset_sent', "Sent a password reset link to {$user->name}.");

        return back()->with('status', "A password reset link has been sent to {$user->email}.");
    }

    private function removesLastActiveSuperAdministrator(User $user, array $data): bool
    {
        if (! $user->isSuperAdministrator() || ! $user->is_active) {
            return false;
        }

        if ($data['role'] === User::ROLE_SUPER_ADMINISTRATOR && $data['is_active']) {
            return false;
        }

        return ! User::query()
            ->where('role', User::ROLE_SUPER_ADMINISTRATOR)
            ->where('is_active', true)
            ->whereKeyNot($user->getKey())
            ->exists();
    }
}
