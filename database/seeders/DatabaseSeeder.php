<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\StudentParentProfile;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesPermissionsSeeder::class);

        /** Create user as admin */
        $user = User::firstOrCreate([
            'name' => 'Hermansyah',
            'email' => 'me@hermansyah.dev',
            'username' => 'me@hermansyah.dev',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $user->assignRole(['super-admin']); // Role as super admin

        /** User with admin role */
        $user = User::firstOrCreate([
            'name' => fake()->name(),
            'email' => 'admin@hermansyah.dev',
            'username' => 'admin@hermansyah.dev',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $user->assignRole(['admin']); // Role as admin

        /** User with teacher role */
        $user = User::firstOrCreate([
            'name' => fake()->name(),
            'email' => 'teacher@hermansyah.dev',
            'username' => 'teacher@hermansyah.dev',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $user->assignRole(['user', 'teacher']); // Role as admin
        $profile = TeacherProfile::create();
        DB::table('user_has_profiles')->insert(['user_id' => $user->id, 'profile_id' => $profile->id, 'profile_type' => TeacherProfile::class]);

        /** User with student role */
        $user = User::firstOrCreate([
            'name' => fake()->name(),
            'email' => 'student@hermansyah.dev',
            'username' => 'student@hermansyah.dev',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        $user->assignRole(['user', 'student']); // Role as admin
        $profile = StudentProfile::create();
        DB::table('user_has_profiles')->insert(['user_id' => $user->id, 'profile_id' => $profile->id, 'profile_type' => StudentProfile::class]);

        /** User with user role */
        $roles = ['admin', 'user', 'teacher', 'student'];
        $users = User::factory(20)->make();
        foreach ($users as $user) {
            $this->createUser($user);
            $randomRole = random_int(0, count($roles) - 1);
            if ($randomRole > 1) {
                $user->assignRole(['user', $roles[$randomRole]]);
            } else {
                $user->assignRole([$roles[$randomRole]]);
            }

            if ($roles[$randomRole] === 'teacher'){
                $profile = TeacherProfile::create();
                DB::table('user_has_profiles')->insert(['user_id' => $user->id, 'profile_id' => $profile->id, 'profile_type' => TeacherProfile::class]);
            }
            if ($roles[$randomRole] === 'student'){
                $profile = StudentProfile::create();
                DB::table('user_has_profiles')->insert(['user_id' => $user->id, 'profile_id' => $profile->id, 'profile_type' => StudentProfile::class]);
            }
        }
    }

    private function createUser($data = null)
    {
        if (!$data) $data = User::factory(1)->make()[0];
        try {
            $data->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->createUser();
        }
    }
}
