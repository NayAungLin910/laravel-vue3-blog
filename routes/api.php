<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

/* private routes */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('api.user');

Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('api.logout');
