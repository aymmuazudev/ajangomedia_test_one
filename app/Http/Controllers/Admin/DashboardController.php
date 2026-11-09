<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'administrators' => User::whereIn('role', User::ADMINISTRATIVE_ROLES)->count(),
                'reporters' => User::where('role', User::ROLE_NEWS_REPORTER)->count(),
            ],
            'recentActivity' => ActivityLog::with('user')->latest('id')->limit(10)->get(),
        ]);
    }
}
