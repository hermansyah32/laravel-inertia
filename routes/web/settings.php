<?php

use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/settings', function () {
    return redirect(route('settings.users.index'));
})->name('settings');

Route::get('/settings/data/users', [UserController::class, 'index'])->name('settings.users.index');
Route::get('/settings/trashed/users', [UserController::class, 'indexTrashed'])->name('settings.trashed.users.index');
Route::get('/settings/data/users/new', [UserController::class, 'create'])->name('settings.users.create');
Route::post('/settings/data/users', [UserController::class, 'store'])->name('settings.users.store');
Route::get('/settings/data/users/{id}', [UserController::class, 'show'])->name('settings.users.show');
Route::get('/settings/trashed/users{id}', [UserController::class, 'showTrashed'])->name('settings.trashed.users.show');
Route::get('/settings/data/users/edit/{id}', [UserController::class, 'edit'])->name('settings.users.edit');
Route::get('/settings/trashed/users/edit/{id}', [UserController::class, 'editTrashed'])->name('settings.trashed.users.edit');
Route::match(['PUT', 'PATCH'], '/settings/data/users/reset/{id}', [UserController::class, 'reset'])->name('settings.users.reset');
Route::match(['PUT', 'PATCH'], '/settings/data/users/edit/{id}', [UserController::class, 'update'])->name('settings.users.update');
Route::match(['PUT', 'PATCH'], '/settings/trashed/users/edit/{id}', [UserController::class, 'restore'])->name('settings.trashed.users.restore');
Route::delete('/settings/data/users{id}', [UserController::class, 'destroy'])->name('settings.users.destroy');
Route::delete('/settings/trashed/users{id}', [UserController::class, 'permanentDestroy'])->name('settings.trashed.users.destroy');

Route::get('/settings/roles', [RoleController::class, 'index'])->name('settings.roles');

Route::get('/settings/permissions', [PermissionController::class, 'index'])->name('settings.permission');
