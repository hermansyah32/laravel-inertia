<?php

use App\Models\PivotProfiles;
use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

Artisan::command('testcode', function () {
    // $result = app('auth.password.broker')->createToken(User::first());
    // $result = hash_hmac('sha512', 'this is me', config('app.key'), false);
    // $hashed = hash_hmac('sha512', 'this is me', config('app.key'), false);
    // $result = hash_equals($token, $hashed);

    $result = User::permission('managed by teacher')->get();
    // $result = User::all();
    // $result = User::with('profile')->get();
    // $result = User::where('id', '97e5a6c9-5573-4afc-af9f-4218bfb63d1a')->with('profiles')->whereHas('profiles', function($query){

    // })->get();
    // $result = PivotProfiles::get();
    // PivotProfiles::create(['user_id' => "97e5a6ca-21eb-46fb-809c-4101f33ce3ca", 'profile_id' => Str::uuid()->toString(), 'profile_type' => User::class]);
    // $result = User::with('profiles.profile')->get();
    // foreach ($result as $profile) {
    //     dd($profile->profile);
    // }
    // $result = User::where('id', '52990d19-9fb6-494f-bada-9b3a771599ae')->get();
    // $result = Role::where('name', 'admin')->first();
    // $result = User::where('id', '38bdf168-54c7-4845-9833-2459bc886a91')->orderBy('created_at')->first(); // Super admin
    // $result = User::where('id', '045652dc-a705-49e5-a3d3-509091585922')->orderBy('created_at')->first(); // Super admin
    // dd($result->getAllPermissions());
    // $result = $result->hasAnyPermission('managed by super-admin');
    dd($result->toArray());

    // dd(User::with("roles")->whereHas("roles", function ($q) {
    //     $q->whereIn("name", ["student"]);
    // })->take(1)->get()->toArray());
    // DB::connection()->enableQueryLog();

    // $students = Student::take(1)->get()->toArray();
    // dd($students);
    // $queries = DB::getQueryLog();
    // dd($queries);
});

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
