<?php

use App\Http\API\Account\RoleController;
use App\Http\API\Account\UserController;
use Illuminate\Support\Facades\Route;

/**============================= Role ============================================== */
Route::get('roles', [RoleController::class, 'index'])->name('api.roles.index');
Route::get('trashed/roles', [RoleController::class, 'indexTrashed'])->name('api.trashed.roles.index');
Route::post('roles', [RoleController::class, 'store'])->name('api.roles.store');
Route::get('roles/{id}', [RoleController::class, 'show'])->name('api.roles.show');
Route::get('trashed/roles/{id}', [RoleController::class, 'showTrashed'])->name('api.trashed.roles.show');
Route::match(['put', 'patch'], 'roles/{id}', [RoleController::class, 'update'])->name('api.roles.update');
Route::match(['put', 'patch'], 'trashed/roles/{id}', [RoleController::class, 'restore'])->name('api.trashed.roles.restore');
Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('api.roles.destroy');
Route::delete('trashed/roles/{id}', [RoleController::class, 'permanentDestroy'])->name('api.trashed.roles.destroy');


/**============================= User ============================================== */
Route::get('users', [UserController::class, 'index'])->name('api.users.index');
Route::get('trashed/users', [UserController::class, 'indexTrashed'])->name('api.trashed.users.index');
Route::post('users', [UserController::class, 'store'])->name('api.users.store');
Route::get('users/{id}', [UserController::class, 'show'])->name('api.users.show');
Route::get('trashed/users/{id}', [UserController::class, 'showTrashed'])->name('api.trashed.users.show');
Route::match(['put', 'patch'], 'users/{id}', [UserController::class, 'update'])->name('api.users.update');
Route::match(['put', 'patch'], 'trashed/users/{id}', [UserController::class, 'restore'])->name('api.trashed.users.restore');
Route::delete('users/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
Route::delete('trashed/users/{id}', [UserController::class, 'permanentDestroy'])->name('api.trashed.users.destroy');
Route::match(['PUT', 'PATCH'],'reset/users/{id}', [UserController::class, 'reset'])->name('api.users.reset');
