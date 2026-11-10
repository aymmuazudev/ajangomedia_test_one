<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'create'])->name('admin.login');
        Route::post('login', [LoginController::class, 'store'])->name('admin.login.store');
        Route::get('forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->name('admin.')->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    });

    Route::middleware(['auth', 'active', 'role:'.implode(',', User::DASHBOARD_ROLES)])
        ->name('admin.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
            Route::post('users/{user}/password-reset', [UserController::class, 'sendPasswordReset'])->name('users.password-reset');
            Route::resource('articles', ArticleController::class)->except(['show']);
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('tags', TagController::class)->only(['index', 'edit', 'update', 'destroy']);
            Route::resource('media', MediaController::class)
                ->only(['index', 'store', 'destroy'])
                ->parameters(['media' => 'mediaFile']);
        });
});
