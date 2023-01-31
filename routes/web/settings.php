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

Route::get('/settings/data/roles', [RoleController::class, 'index'])->name('settings.roles.index');
Route::get('/settings/trashed/roles', [RoleController::class, 'indexTrashed'])->name('settings.trashed.roles.index');
Route::get('/settings/data/roles/new', [RoleController::class, 'create'])->name('settings.roles.create');
Route::post('/settings/data/roles', [RoleController::class, 'store'])->name('settings.roles.store');
Route::get('/settings/data/roles/{id}', [RoleController::class, 'show'])->name('settings.roles.show');
Route::get('/settings/trashed/roles{id}', [RoleController::class, 'showTrashed'])->name('settings.trashed.roles.show');
Route::get('/settings/data/roles/edit/{id}', [RoleController::class, 'edit'])->name('settings.roles.edit');
Route::get('/settings/trashed/roles/edit/{id}', [RoleController::class, 'editTrashed'])->name('settings.trashed.roles.edit');
Route::match(['PUT', 'PATCH'], '/settings/data/roles/reset/{id}', [RoleController::class, 'reset'])->name('settings.roles.reset');
Route::match(['PUT', 'PATCH'], '/settings/data/roles/edit/{id}', [RoleController::class, 'update'])->name('settings.roles.update');
Route::match(['PUT', 'PATCH'], '/settings/trashed/roles/edit/{id}', [RoleController::class, 'restore'])->name('settings.trashed.roles.restore');
Route::delete('/settings/data/roles{id}', [RoleController::class, 'destroy'])->name('settings.roles.destroy');
Route::delete('/settings/trashed/roles{id}', [RoleController::class, 'permanentDestroy'])->name('settings.trashed.roles.destroy');

Route::get('/settings/permissions', [PermissionController::class, 'index'])->name('settings.permission');
