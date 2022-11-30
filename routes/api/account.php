<?php

/**============================= Account Profile ============================================== */

use App\Http\API\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('account', [ProfileController::class, 'show'])
    ->name('api.account.show');
Route::match(['put', 'patch'], 'account', [ProfileController::class, 'update'])
    ->name('api.account.update');
Route::match(['put', 'patch'], 'account/profile', [ProfileController::class, 'updateProfile'])
    ->name('api.account.profile');
Route::match(['put', 'patch'], 'account/password', [ProfileController::class, 'updatePassword'])
    ->name('api.account.password');
