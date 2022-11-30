<?php

namespace App\Helper;

use Illuminate\Support\Facades\Lang;

class Constants
{
    public static function GENDER()
    {
        return [
            ['id' => 'male', 'name' => Lang::get('Male')],
            ['id' => 'female', 'name' => Lang::get('Female')],
        ];
    }

    public static function PERMISSIONS()
    {
        return ((object)[
            // Settings
            'users' => ((object)[
                'manage' => 'users_manage',
                'index' => 'users_index',
                'index_trashed' => 'users_index_trashed',
                'store' => 'users_store',
                'show' => 'users_show',
                'show_trashed' => 'users_show_trashed',
                'update' => 'users_update',
                'restore' => 'users_restore',
                'destroy' => 'users_destroy',
                'permanent_destroy' => 'users_permanent_destroy',
                'reset' => 'users_reset'
            ]),
            'roles' => ((object)[
                'manage' => 'roles_manage',
                'index' => 'roles_index',
                'index_trashed' => 'roles_index_trashed',
                'store' => 'roles_store',
                'show' => 'roles_show',
                'show_trashed' => 'roles_show_trashed',
                'update' => 'roles_update',
                'restore' => 'roles_restore',
                'destroy' => 'roles_destroy',
                'permanent_destroy' => 'roles_permanent_destroy',
            ]),
            'permissions' => ((object)[
                'manage' => 'manage',
            ]),
            // Class
            'student_classes' => ((object)[
                'manage' => 'student_classes_manage',
                'index' => 'student_classes_index',
                'index_trashed' => 'student_classes_index_trashed',
                'store' => 'student_classes_store',
                'show' => 'student_classes_show',
                'show_trashed' => 'student_classes_show_trashed',
                'update' => 'student_classes_update',
                'restore' => 'student_classes_restore',
                'destroy' => 'student_classes_destroy',
                'permanent_destroy' => 'student_classes_permanent_destroy',
            ]),
            'student_department' => ((object)[
                'manage' => 'student_department_manage',
                'index' => 'student_department_index',
                'index_trashed' => 'student_department_index_trashed',
                'store' => 'student_department_store',
                'show' => 'student_department_show',
                'show_trashed' => 'student_department_show_trashed',
                'update' => 'student_department_update',
                'restore' => 'student_department_restore',
                'destroy' => 'student_department_destroy',
                'permanent_destroy' => 'student_department_permanent_destroy',
            ]),
            'student_grades' => ((object)[
                'manage' => 'student_grades_manage',
                'index' => 'student_grades_index',
                'index_trashed' => 'student_grades_index_trashed',
                'store' => 'student_grades_store',
                'show' => 'student_grades_show',
                'show_trashed' => 'student_grades_show_trashed',
                'update' => 'student_grades_update',
                'restore' => 'student_grades_restore',
                'destroy' => 'student_grades_destroy',
                'permanent_destroy' => 'student_grades_permanent_destroy',
            ]),
            // Content
            'subject' => ((object)[
                'manage' => 'subject_manage',
                'index' => 'subject_index',
                'index_trashed' => 'subject_index_trashed',
                'store' => 'subject_store',
                'show' => 'subject_show',
                'show_trashed' => 'subject_show_trashed',
                'update' => 'subject_update',
                'restore' => 'subject_restore',
                'destroy' => 'subject_destroy',
                'permanent_destroy' => 'subject_permanent_destroy',
            ]),
            'subject_contents' => ((object)[
                'manage' => 'subject_contents_manage',
                'index' => 'subject_contents_index',
                'index_trashed' => 'subject_contents_index_trashed',
                'store' => 'subject_contents_store',
                'show' => 'subject_contents_show',
                'show_trashed' => 'subject_contents_show_trashed',
                'update' => 'subject_contents_update',
                'restore' => 'subject_contents_restore',
                'destroy' => 'subject_contents_destroy',
                'permanent_destroy' => 'subject_contents_permanent_destroy',
            ]),
            'subject_groups' => ((object)[
                'manage' => 'manage',
                'index' => 'subject_groups_index',
                'index_trashed' => 'subject_groups_index_trashed',
                'store' => 'subject_groups_store',
                'show' => 'subject_groups_show',
                'show_trashed' => 'subject_groups_show_trashed',
                'update' => 'subject_groups_update',
                'restore' => 'subject_groups_restore',
                'destroy' => 'subject_groups_destroy',
                'permanent_destroy' => 'subject_groups_permanent_destroy',
            ]),
            'subject_references' => ((object)[
                'manage' => 'manage',
                'index' => 'subject_references_index',
                'index_trashed' => 'subject_references_index_trashed',
                'store' => 'subject_references_store',
                'show' => 'subject_references_show',
                'show_trashed' => 'subject_references_show_trashed',
                'update' => 'subject_references_update',
                'restore' => 'subject_references_restore',
                'destroy' => 'subject_references_destroy',
                'permanent_destroy' => 'subject_references_permanent_destroy',
            ]),
            // Assignment
            'assignment_groups' => ((object)[
                'manage' => 'manage',
                'index' => 'assignment_groups_index',
                'index_trashed' => 'assignment_groups_index_trashed',
                'store' => 'assignment_groups_store',
                'show' => 'assignment_groups_show',
                'show_trashed' => 'assignment_groups_show_trashed',
                'update' => 'assignment_groups_update',
                'restore' => 'assignment_groups_restore',
                'destroy' => 'assignment_groups_destroy',
                'permanent_destroy' => 'assignment_groups_permanent_destroy',
            ]),
            'student_assignments' => ((object)[
                'manage' => 'manage',
                'index' => 'student_assignments_index',
                'index_trashed' => 'student_assignments_index_trashed',
                'store' => 'student_assignments_store',
                'show' => 'student_assignments_show',
                'show_trashed' => 'student_assignments_show_trashed',
                'update' => 'student_assignments_update',
                'restore' => 'student_assignments_restore',
                'destroy' => 'student_assignments_destroy',
                'permanent_destroy' => 'student_assignments_permanent_destroy',
            ]),
            'subject_assignments' => ((object)[
                'manage' => 'manage',
                'index' => 'subject_assignments_index',
                'index_trashed' => 'subject_assignments_index_trashed',
                'store' => 'subject_assignments_store',
                'show' => 'subject_assignments_show',
                'show_trashed' => 'subject_assignments_show_trashed',
                'update' => 'subject_assignments_update',
                'restore' => 'subject_assignments_restore',
                'destroy' => 'subject_assignments_destroy',
                'permanent_destroy' => 'subject_assignments_permanent_destroy',
            ]),
        ]);
    }
}
