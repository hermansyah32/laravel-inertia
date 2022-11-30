<?php

use App\Http\Controllers\Dashboard\OverviewController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return redirect(route('dashboard.overview'));
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard/overview', [OverviewController::class, 'overview'])->middleware(['auth'])->name('dashboard.overview');