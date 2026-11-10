<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Password::defaults(fn () => Password::min(12)->letters()->mixedCase()->numbers()->symbols());

        View::composer('layouts.public', function ($view) {
            $view->with('navigationCategories', Category::whereNull('parent_id')
                ->orderBy('display_order')
                ->get(['id', 'name', 'slug']));
        });
    }
}
