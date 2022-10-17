<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
        $role = Role::find(1);
        $user->assignRole([$role->id]);

        /** User with user role */
        $users = User::factory(100)->make();
        foreach ($users as $user) {
            $this->createUser($user);
        }
    }

    private function createUser($data = null)
    {
        if ($data === null) $data = User::factory(1)->make()[0];
        try {
            $data->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->createUser();
        }
    }
}
