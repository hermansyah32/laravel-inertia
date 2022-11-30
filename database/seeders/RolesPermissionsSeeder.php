<?php

namespace Database\Seeders;

use App\Helper\Constants;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**==================================== Create Role ============================================= */
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        /**==================================== Create Permissions ============================================= */
        $permissions = ((array) Constants::PERMISSIONS());
        foreach ($permissions as $key => $permission) {
            $permissionRule = ((array) $permission);
            foreach ($permissionRule as $key => $rule) {
                Permission::firstOrCreate(['name' => $rule]);
            }
        }

        /**==================================== Manage by Role ============================================= */
        $managePermission = [
            'super-admin' => 'managed by super-admin',
            'admin' => 'managed by admin',
            'user' => 'managed by user',
            'teacher' => 'managed by teacher',
            'student' => 'managed by student',
        ];
        foreach ($managePermission as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
        }
        $adminRole->givePermissionTo($managePermission['super-admin']);
        $userRole->givePermissionTo($managePermission['super-admin'], $managePermission['admin']);
        $teacherRole->givePermissionTo($managePermission['super-admin'], $managePermission['admin'], $managePermission['user']);
        $studentRole->givePermissionTo($managePermission['super-admin'], $managePermission['admin'], $managePermission['user'], $managePermission['teacher']);
    }
}
