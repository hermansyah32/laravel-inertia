<?php

use App\Http\Controllers\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile/edit', [ProfileController::class, 'updateProfile'])->name('profile.update');
// Route::get('/account', [ProfileController::class, 'showAccount'])->name('account'); // not implement yet
// Route::get('/account/edit', [ProfileController::class, 'editAccount'])->name('account.edit');
Route::get('/account/security', [ProfileController::class, 'editSecurity'])->name('account.security');
Route::put('/account/security', [ProfileController::class, 'updateSecurity'])->name('account.security.update');
