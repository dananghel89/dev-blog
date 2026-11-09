<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Privacy, terms, the sitemap and the newsletter don't exist yet (real 404s);
| they come back with the admin.
|
*/

// Main pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category:slug}', [BlogController::class, 'byCategory'])->name('category');
    Route::get('/tag/{tag:slug}', [BlogController::class, 'byTag'])->name('tag');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});
