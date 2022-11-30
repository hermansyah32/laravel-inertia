<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    require __DIR__ . '/api/account.php';
    require __DIR__ . "/api/settings.php";
    require __DIR__ . "/api/apps/class.php";
    require __DIR__ . "/api/apps/content.php";
    require __DIR__ . "/api/apps/assignment.php";
});
