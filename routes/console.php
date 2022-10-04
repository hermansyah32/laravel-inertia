<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:clear', function () {
    Artisan::call("cache:clear");
    $this->info("Cache cleared");
    Artisan::call("config:clear");
    $this->info("Config cleared");
    Artisan::call("view:clear");
    $this->info("View cleared");
    Artisan::call("route:clear");
    $this->info("Route cleared");
    array_map('unlink', glob(storage_path() . "\\temp*" . ".html"));
    array_map('unlink', glob(storage_path() . "\\temp*" . ".pdf"));
    $this->info("Temporary files cleared");
})->purpose("Clear cache, config and view");

Artisan::command('app:install {--R|reset}', function ($reset) {
    if ($reset) {
        $this->comment("Application reset");
    } else {
        Artisan::call('migrate');
        $this->info("Database migrated");
        Artisan::call('db:seed');
        $this->info("Database seed");
        Artisan::call('storage;link');
        $this->info("Symbolic link finished");
        Artisan::call('manifest:generate');
        $this->info('Logo & manifest file generated');
        $this->info("Application installed");
    }
})->purpose('Clear data');
