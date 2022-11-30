<?php

use App\Http\API\Auth\AuthenticatedSessionController;
use App\Http\API\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('auth/register', [RegisteredUserController::class, 'register'])->name('api.register');
    Route::post('auth/login', [AuthenticatedSessionController::class, 'login'])->name('api.login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/token/refresh', [AuthenticatedSessionController::class, 'reissueToken'])
        ->name('api.token.refresh');
    Route::post('auth/logout', [AuthenticatedSessionController::class, 'logout'])
        ->name('api.logout');
});
