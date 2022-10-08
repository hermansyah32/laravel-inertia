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
        $user = Role::firstOrCreate(['name' => 'User']);


        $roles = ['superadmin', 'admin', 'user'];
        $accountPermissions = ['store', 'show', 'update', 'destroy', 'permanentDestroy', 'reset', 'recover'];
        foreach ($roles as $role) {
            switch ($role) {
                case 'superadmin':
                    foreach ($accountPermissions as $permissionName) {
                        $permission = Permission::firstOrCreate(['name' => $role . '.' . $permissionName]);
                    }
                    break;
                case 'admin':
                    foreach ($accountPermissions as $permissionName) {
                        $permission = Permission::firstOrCreate(['name' => $role . '.' . $permissionName]);
                        $superAdminRole->givePermissionTo($permission);
                    }
                    break;
                case 'user':
                    foreach ($accountPermissions as $permissionName) {
                        $permission = Permission::firstOrCreate(['name' => $role . '.' . $permissionName]);
                        $superAdminRole->givePermissionTo($permission);
                        $adminRole->givePermissionTo($permission);
                    }
                    break;
                default:
                    break;
            }
        }
    }
}
