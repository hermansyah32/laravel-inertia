<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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

Artisan::command('init:sentry', function () {
    Artisan::call('sentry:publish --dsn=https://8fce4597dd3b41df970e16febb86384e@o1008265.ingest.sentry.io/4503947724718080');
    $this->info('Sentry configured');
    Artisan::call('sentry:test');
    $this->info('Sentry tested');
})->purpose('init sentry configuration');

Artisan::command('app:install {--R|reset}', function ($reset) {
    if ($reset) {
        Artisan::call('migrate:fresh');
        $this->info("Database migrated");
    } else {
        Artisan::call('migrate');
        $this->info("Database migrated");
    }

    Artisan::call('db:seed');
    $this->info("Database seed");
    Artisan::call('manifest:generate');
    $this->info('Logo & manifest file generated');
    if (env("APP_ENV") === "local") {
        copy(resource_path("core/index-local.php"), base_path("public/index.php"));
        copy(resource_path("core/bootstrap-local.php"), base_path("bootstrap/app.php"));
        File::copyDirectory(base_path("public"), dirname(base_path(), 2) . "/inertia");
    } else {
        copy(resource_path("core/index-prod.php"), base_path("public/index.php"));
        copy(resource_path("core/bootstrap-prod.php"), base_path("bootstrap/app.php"));
        File::copyDirectory(base_path("public"), dirname(base_path(), 2) . "/inertia");
    }
    $this->info('Move public folder');
    Artisan::call('storage:link');
    $this->info("Symbolic link finished");
    $this->info("Application installed");
})->purpose('Clear data');
