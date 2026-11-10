<?php

use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MediaController;
use App\Http\Controllers\Public\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/news/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/media/{path}', [MediaController::class, 'show'])->where('path', '.+')->name('media.show');

require __DIR__.'/admin.php';
