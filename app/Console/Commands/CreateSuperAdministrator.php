<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateSuperAdministrator extends Command
{
    protected $signature = 'admin:create-super-administrator';

    protected $description = 'Create a Super Administrator account for the administration area';

    public function handle(): int
    {
        $input = [
            'name' => $this->ask('Full name'),
            'email' => $this->ask('Email address'),
            'password' => $this->secret('Password (at least 12 characters, mixed case, numbers and symbols)'),
        ];

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => User::ROLE_SUPER_ADMINISTRATOR,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        ActivityLog::record(null, 'user.super_administrator_created', "Created Super Administrator {$user->email} from the command line.");
        $this->info('Super Administrator account created.');

        return self::SUCCESS;
    }
}
