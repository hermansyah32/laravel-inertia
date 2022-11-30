<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

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
        $user = User::where('email', 'me@hermansyah.dev')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Hermansyah',
                'email' => 'me@hermansyah.dev',
                'username' => 'me@hermansyah.dev',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]);
        }
        $user->assignRole(['super-admin']); // Role as super admin

        /** User with user role */
        $roles = ['admin', 'user', 'teacher', 'student'];
        $users = User::factory(100)->make();
        foreach ($users as $user) {
            $this->createUser($user);
            $randomRole = random_int(0, count($roles) - 1);
            if ($randomRole > 1) {
                $user->assignRole(['user', $roles[$randomRole]]);
            } else {
                $user->assignRole([$roles[$randomRole]]);
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
