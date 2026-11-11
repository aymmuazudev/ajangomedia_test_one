<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveAdvertisementRequest;
use App\Models\ActivityLog;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    private const PER_PAGE = 20;

    public function index(): View
    {
        $this->authorize('viewAny', Advertisement::class);

        $advertisements = Advertisement::query()
            ->with('creator:id,name')
            ->latest('id')
            ->paginate(self::PER_PAGE);

        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create(): View
    {
        $this->authorize('create', Advertisement::class);

        return view('admin.advertisements.create', [
            'advertisement' => new Advertisement([
                'placement' => Advertisement::PLACEMENT_HOME_TOP,
                'format' => 'leaderboard',
                'is_active' => true,
            ]),
        ]);
    }

    public function store(SaveAdvertisementRequest $request): RedirectResponse
    {
        $advertisement = Advertisement::create([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
        ]);

        ActivityLog::record($request->user(), 'advertisement.created', "Created the advertisement {$advertisement->name}.");

        return redirect()->route('admin.advertisements.index')
            ->with('status', "The advertisement {$advertisement->name} has been created.");
    }

    public function edit(Advertisement $advertisement): View
    {
        $this->authorize('update', $advertisement);

        return view('admin.advertisements.edit', [
            'advertisement' => $advertisement,
        ]);
    }

    public function update(SaveAdvertisementRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $advertisement->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::record($request->user(), 'advertisement.updated', "Updated the advertisement {$advertisement->name}.");

        return redirect()->route('admin.advertisements.index')
            ->with('status', "The advertisement {$advertisement->name} has been updated.");
    }

    public function destroy(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $this->authorize('delete', $advertisement);

        $name = $advertisement->name;
        $advertisement->delete();

        ActivityLog::record($request->user(), 'advertisement.deleted', "Deleted the advertisement {$name}.");

        return redirect()->route('admin.advertisements.index')
            ->with('status', "The advertisement {$name} has been deleted.");
    }
}
