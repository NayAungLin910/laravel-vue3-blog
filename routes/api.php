<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RelatedPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* public routes */

Route::post('register', [RegisteredUserController::class, 'store'])->name('api.register');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('api.login');

// posts api
Route::prefix('posts')->as('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/home-posts', [HomeController::class, 'index'])->name('home-posts');
    Route::get('/{post:slug}', [PostController::class, 'show'])->name('show');
    Route::prefix('related-posts')->as('related-posts.')->group(function () {
        Route::get('/{post:slug}', [RelatedPostController::class, 'index'])->name('index');
    });
    Route::prefix('dashboard-posts')->as('dashboard-posts.')->group(function () {
        Route::get('/all', [DashboardPostController::class, 'index'])->name('index');
    });
});

/* private routes */

Route::middleware(['auth:sanctum'])->as('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('api.logout');

    // categories routes
    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/create', [CategoryController::class, 'store'])->name('create');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // posts routes
    Route::prefix('posts')->as('posts.')->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('create');
    });
});
