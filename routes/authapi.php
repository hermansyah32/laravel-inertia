<?php

use App\Http\API\Auth\AuthenticatedSessionController;
use App\Http\API\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'register']);
    Route::post('login', [AuthenticatedSessionController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'logout'])
                ->name('logout');
});
