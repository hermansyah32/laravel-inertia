<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        /**==================================== User Permission Role ============================================= */
        $userPermissions = [
            'index' => 'can index users',
            'indexTrashed' => 'can index trashed users',
            'get' => 'can get users',
            'getFull' => 'can get full users',
            'getTrashed' => 'can get trashed users',
            'update' => 'can update users',
            'restore' => 'can restore users',
            'destroy' => 'can destroy users',
            'permanentDestroy' => 'can permanentDestroy users',
            'reset' => 'can reset users',
        ];
        foreach ($userPermissions as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
            $superAdminRole->givePermissionTo($permission);
            $adminRole->givePermissionTo($permission);
        }

        /**==================================== Role Permission Role ============================================= */
        $rolePermissions = [
            'index' => 'can index roles',
            'indexTrashed' => 'can index trashed roles',
            'get' => 'can get roles',
            'getFull' => 'can get full roles',
            'getTrashed' => 'can get trashed roles',
            'update' => 'can update roles',
            'restore' => 'can restore roles',
            'destroy' => 'can destroy roles',
            'permanentDestroy' => 'can permanentDestroy roles',
        ];
        foreach ($rolePermissions as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
            $superAdminRole->givePermissionTo($permission);
        }
    }
}
