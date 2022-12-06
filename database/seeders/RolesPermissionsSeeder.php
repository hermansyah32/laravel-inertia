<?php

namespace Database\Seeders;

use App\Helper\Constants;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as ModelsRole;

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
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'rank' => 1, 'permission_tag' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'rank' => 2, 'permission_tag' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'rank' => 999, 'permission_tag' => 'user']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'rank' => 3, 'permission_tag' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'permission_tag' => 'student']);
        $studentParentRole = Role::firstOrCreate(['name' => 'student-parent', 'permission_tag' => 'student-parent']);

        /**==================================== Create Permissions ============================================= */
        $permissions = ((array) Constants::PERMISSIONS());
        foreach ($permissions as $key => $permission) {
            $permissionRule = ((array) $permission);
            foreach ($permissionRule as $key => $rule) {
                $permissionModel = Permission::firstOrCreate(['name' => $rule]);
            }
        }

        /**==================================== Manage by Role ============================================= */
        $managePermission = [
            'super-admin' => 'managed by super-admin',
            'admin' => 'managed by admin',
            'user' => 'managed by user',
            'teacher' => 'managed by teacher',
            'student' => 'managed by student',
            'student-parent' => 'managed by student-parent',
        ];
        $permissions = [];
        foreach ($managePermission as $key => $permission) {
            $permissions[$key] = Permission::firstOrCreate(['name' => $permission]);
        }
        
        $adminRole->givePermissionTo($permissions['super-admin']);
        $userRole->givePermissionTo($permissions['super-admin'], $permissions['admin']);
        $teacherRole->givePermissionTo($permissions['super-admin'], $permissions['admin'], $permissions['user']);
        $studentRole->givePermissionTo($permissions['super-admin'], $permissions['admin'], $permissions['user'], $permissions['teacher']);
        $studentParentRole->givePermissionTo($permissions['super-admin'], $permissions['admin']);
    }
}
