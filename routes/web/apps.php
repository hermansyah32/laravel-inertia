<?php

use App\Http\Controllers\Apps\Assignment\AssignmentGroupController;
use App\Http\Controllers\Apps\Assignment\StudentAssignmentController;
use App\Http\Controllers\Apps\Assignment\SubjectAssignmentController;
use App\Http\Controllers\Apps\Class\StudentClassController;
use App\Http\Controllers\Apps\Class\StudentDepartmentController;
use App\Http\Controllers\Apps\Class\StudentGradeController;
use App\Http\Controllers\Apps\Content\SubjectContentController;
use App\Http\Controllers\Apps\Content\SubjectGroupController;
use App\Http\Controllers\Apps\Content\SubjectReferenceController;
use Illuminate\Support\Facades\Route;

// default apps route
Route::get('/apps', function () {
    return redirect(route('apps.grade'));
})->name('apps');

Route::get('/apps/data/studentgrades', [StudentGradeController::class, 'index'])->name('apps.studentgrades.index');
Route::get('/apps/trashed/studentgrades', [StudentGradeController::class, 'indexTrashed'])->name('apps.trashed.studentgrades.index');
Route::get('/apps/data/studentgrades/new', [StudentGradeController::class, 'create'])->name('apps.studentgrades.create');
Route::post('/apps/data/studentgrades', [StudentGradeController::class, 'store'])->name('apps.studentgrades.store');
Route::get('/apps/data/studentgrades/{id}', [StudentGradeController::class, 'show'])->name('apps.studentgrades.show');
Route::get('/apps/trashed/studentgrades{id}', [StudentGradeController::class, 'showTrashed'])->name('apps.trashed.studentgrades.show');
Route::get('/apps/data/studentgrades/edit/{id}', [StudentGradeController::class, 'edit'])->name('apps.studentgrades.edit');
Route::get('/apps/trashed/studentgrades/edit/{id}', [StudentGradeController::class, 'editTrashed'])->name('apps.trashed.studentgrades.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/studentgrades/reset/{id}', [StudentGradeController::class, 'reset'])->name('apps.studentgrades.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/studentgrades/edit/{id}', [StudentGradeController::class, 'update'])->name('apps.studentgrades.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/studentgrades/edit/{id}', [StudentGradeController::class, 'restore'])->name('apps.trashed.studentgrades.restore');
Route::delete('/apps/data/studentgrades{id}', [StudentGradeController::class, 'destroy'])->name('apps.studentgrades.destroy');
Route::delete('/apps/trashed/studentgrades{id}', [StudentGradeController::class, 'permanentDestroy'])->name('apps.trashed.studentgrades.destroy');

Route::get('/apps/data/studentclasses', [StudentClassController::class, 'index'])->name('apps.studentclasses.index');
Route::get('/apps/trashed/studentclasses', [StudentClassController::class, 'indexTrashed'])->name('apps.trashed.studentclasses.index');
Route::get('/apps/data/studentclasses/new', [StudentClassController::class, 'create'])->name('apps.studentclasses.create');
Route::post('/apps/data/studentclasses', [StudentClassController::class, 'store'])->name('apps.studentclasses.store');
Route::get('/apps/data/studentclasses/{id}', [StudentClassController::class, 'show'])->name('apps.studentclasses.show');
Route::get('/apps/trashed/studentclasses{id}', [StudentClassController::class, 'showTrashed'])->name('apps.trashed.studentclasses.show');
Route::get('/apps/data/studentclasses/edit/{id}', [StudentClassController::class, 'edit'])->name('apps.studentclasses.edit');
Route::get('/apps/trashed/studentclasses/edit/{id}', [StudentClassController::class, 'editTrashed'])->name('apps.trashed.studentclasses.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/studentclasses/reset/{id}', [StudentClassController::class, 'reset'])->name('apps.studentclasses.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/studentclasses/edit/{id}', [StudentClassController::class, 'update'])->name('apps.studentclasses.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/studentclasses/edit/{id}', [StudentClassController::class, 'restore'])->name('apps.trashed.studentclasses.restore');
Route::delete('/apps/data/studentclasses{id}', [StudentClassController::class, 'destroy'])->name('apps.studentclasses.destroy');
Route::delete('/apps/trashed/studentclasses{id}', [StudentClassController::class, 'permanentDestroy'])->name('apps.trashed.studentclasses.destroy');

Route::get('/apps/data/studentdepartments', [StudentDepartmentController::class, 'index'])->name('apps.studentdepartments.index');
Route::get('/apps/trashed/studentdepartments', [StudentDepartmentController::class, 'indexTrashed'])->name('apps.trashed.studentdepartments.index');
Route::get('/apps/data/studentdepartments/new', [StudentDepartmentController::class, 'create'])->name('apps.studentdepartments.create');
Route::post('/apps/data/studentdepartments', [StudentDepartmentController::class, 'store'])->name('apps.studentdepartments.store');
Route::get('/apps/data/studentdepartments/{id}', [StudentDepartmentController::class, 'show'])->name('apps.studentdepartments.show');
Route::get('/apps/trashed/studentdepartments{id}', [StudentDepartmentController::class, 'showTrashed'])->name('apps.trashed.studentdepartments.show');
Route::get('/apps/data/studentdepartments/edit/{id}', [StudentDepartmentController::class, 'edit'])->name('apps.studentdepartments.edit');
Route::get('/apps/trashed/studentdepartments/edit/{id}', [StudentDepartmentController::class, 'editTrashed'])->name('apps.trashed.studentdepartments.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/studentdepartments/reset/{id}', [StudentDepartmentController::class, 'reset'])->name('apps.studentdepartments.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/studentdepartments/edit/{id}', [StudentDepartmentController::class, 'update'])->name('apps.studentdepartments.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/studentdepartments/edit/{id}', [StudentDepartmentController::class, 'restore'])->name('apps.trashed.studentdepartments.restore');
Route::delete('/apps/data/studentdepartments{id}', [StudentDepartmentController::class, 'destroy'])->name('apps.studentdepartments.destroy');
Route::delete('/apps/trashed/studentdepartments{id}', [StudentDepartmentController::class, 'permanentDestroy'])->name('apps.trashed.studentdepartments.destroy');

/** Suject Section */
Route::get('/apps/data/subjectgroups', [SubjectGroupController::class, 'index'])->name('apps.subjectgroups.index');
Route::get('/apps/trashed/subjectgroups', [SubjectGroupController::class, 'indexTrashed'])->name('apps.trashed.subjectgroups.index');
Route::get('/apps/data/subjectgroups/new', [SubjectGroupController::class, 'create'])->name('apps.subjectgroups.create');
Route::post('/apps/data/subjectgroups', [SubjectGroupController::class, 'store'])->name('apps.subjectgroups.store');
Route::get('/apps/data/subjectgroups/{id}', [SubjectGroupController::class, 'show'])->name('apps.subjectgroups.show');
Route::get('/apps/trashed/subjectgroups{id}', [SubjectGroupController::class, 'showTrashed'])->name('apps.trashed.subjectgroups.show');
Route::get('/apps/data/subjectgroups/edit/{id}', [SubjectGroupController::class, 'edit'])->name('apps.subjectgroups.edit');
Route::get('/apps/trashed/subjectgroups/edit/{id}', [SubjectGroupController::class, 'editTrashed'])->name('apps.trashed.subjectgroups.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectgroups/reset/{id}', [SubjectGroupController::class, 'reset'])->name('apps.subjectgroups.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectgroups/edit/{id}', [SubjectGroupController::class, 'update'])->name('apps.subjectgroups.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/subjectgroups/edit/{id}', [SubjectGroupController::class, 'restore'])->name('apps.trashed.subjectgroups.restore');
Route::delete('/apps/data/subjectgroups{id}', [SubjectGroupController::class, 'destroy'])->name('apps.subjectgroups.destroy');
Route::delete('/apps/trashed/subjectgroups{id}', [SubjectGroupController::class, 'permanentDestroy'])->name('apps.trashed.subjectgroups.destroy');

Route::get('/apps/data/subjectcontents', [SubjectContentController::class, 'index'])->name('apps.subjectcontents.index');
Route::get('/apps/trashed/subjectcontents', [SubjectContentController::class, 'indexTrashed'])->name('apps.trashed.subjectcontents.index');
Route::get('/apps/data/subjectcontents/new', [SubjectContentController::class, 'create'])->name('apps.subjectcontents.create');
Route::post('/apps/data/subjectcontents', [SubjectContentController::class, 'store'])->name('apps.subjectcontents.store');
Route::get('/apps/data/subjectcontents/{id}', [SubjectContentController::class, 'show'])->name('apps.subjectcontents.show');
Route::get('/apps/trashed/subjectcontents{id}', [SubjectContentController::class, 'showTrashed'])->name('apps.trashed.subjectcontents.show');
Route::get('/apps/data/subjectcontents/edit/{id}', [SubjectContentController::class, 'edit'])->name('apps.subjectcontents.edit');
Route::get('/apps/trashed/subjectcontents/edit/{id}', [SubjectContentController::class, 'editTrashed'])->name('apps.trashed.subjectcontents.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectcontents/reset/{id}', [SubjectContentController::class, 'reset'])->name('apps.subjectcontents.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectcontents/edit/{id}', [SubjectContentController::class, 'update'])->name('apps.subjectcontents.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/subjectcontents/edit/{id}', [SubjectContentController::class, 'restore'])->name('apps.trashed.subjectcontents.restore');
Route::delete('/apps/data/subjectcontents{id}', [SubjectContentController::class, 'destroy'])->name('apps.subjectcontents.destroy');
Route::delete('/apps/trashed/subjectcontents{id}', [SubjectContentController::class, 'permanentDestroy'])->name('apps.trashed.subjectcontents.destroy');

Route::get('/apps/data/subjectreferences', [SubjectReferenceController::class, 'index'])->name('apps.subjectreferences.index');
Route::get('/apps/trashed/subjectreferences', [SubjectReferenceController::class, 'indexTrashed'])->name('apps.trashed.subjectreferences.index');
Route::get('/apps/data/subjectreferences/new', [SubjectReferenceController::class, 'create'])->name('apps.subjectreferences.create');
Route::post('/apps/data/subjectreferences', [SubjectReferenceController::class, 'store'])->name('apps.subjectreferences.store');
Route::get('/apps/data/subjectreferences/{id}', [SubjectReferenceController::class, 'show'])->name('apps.subjectreferences.show');
Route::get('/apps/trashed/subjectreferences{id}', [SubjectReferenceController::class, 'showTrashed'])->name('apps.trashed.subjectreferences.show');
Route::get('/apps/data/subjectreferences/edit/{id}', [SubjectReferenceController::class, 'edit'])->name('apps.subjectreferences.edit');
Route::get('/apps/trashed/subjectreferences/edit/{id}', [SubjectReferenceController::class, 'editTrashed'])->name('apps.trashed.subjectreferences.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectreferences/reset/{id}', [SubjectReferenceController::class, 'reset'])->name('apps.subjectreferences.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectreferences/edit/{id}', [SubjectReferenceController::class, 'update'])->name('apps.subjectreferences.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/subjectreferences/edit/{id}', [SubjectReferenceController::class, 'restore'])->name('apps.trashed.subjectreferences.restore');
Route::delete('/apps/data/subjectreferences{id}', [SubjectReferenceController::class, 'destroy'])->name('apps.subjectreferences.destroy');
Route::delete('/apps/trashed/subjectreferences{id}', [SubjectReferenceController::class, 'permanentDestroy'])->name('apps.trashed.subjectreferences.destroy');

/** Assignment Section */
Route::get('/apps/data/assignmentgroups', [AssignmentGroupController::class, 'index'])->name('apps.assignmentgroups.index');
Route::get('/apps/trashed/assignmentgroups', [AssignmentGroupController::class, 'indexTrashed'])->name('apps.trashed.assignmentgroups.index');
Route::get('/apps/data/assignmentgroups/new', [AssignmentGroupController::class, 'create'])->name('apps.assignmentgroups.create');
Route::post('/apps/data/assignmentgroups', [AssignmentGroupController::class, 'store'])->name('apps.assignmentgroups.store');
Route::get('/apps/data/assignmentgroups/{id}', [AssignmentGroupController::class, 'show'])->name('apps.assignmentgroups.show');
Route::get('/apps/trashed/assignmentgroups{id}', [AssignmentGroupController::class, 'showTrashed'])->name('apps.trashed.assignmentgroups.show');
Route::get('/apps/data/assignmentgroups/edit/{id}', [AssignmentGroupController::class, 'edit'])->name('apps.assignmentgroups.edit');
Route::get('/apps/trashed/assignmentgroups/edit/{id}', [AssignmentGroupController::class, 'editTrashed'])->name('apps.trashed.assignmentgroups.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/assignmentgroups/reset/{id}', [AssignmentGroupController::class, 'reset'])->name('apps.assignmentgroups.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/assignmentgroups/edit/{id}', [AssignmentGroupController::class, 'update'])->name('apps.assignmentgroups.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/assignmentgroups/edit/{id}', [AssignmentGroupController::class, 'restore'])->name('apps.trashed.assignmentgroups.restore');
Route::delete('/apps/data/assignmentgroups{id}', [AssignmentGroupController::class, 'destroy'])->name('apps.assignmentgroups.destroy');
Route::delete('/apps/trashed/assignmentgroups{id}', [AssignmentGroupController::class, 'permanentDestroy'])->name('apps.trashed.assignmentgroups.destroy');

Route::get('/apps/data/subjectassignments', [SubjectAssignmentController::class, 'index'])->name('apps.subjectassignments.index');
Route::get('/apps/trashed/subjectassignments', [SubjectAssignmentController::class, 'indexTrashed'])->name('apps.trashed.subjectassignments.index');
Route::get('/apps/data/subjectassignments/new', [SubjectAssignmentController::class, 'create'])->name('apps.subjectassignments.create');
Route::post('/apps/data/subjectassignments', [SubjectAssignmentController::class, 'store'])->name('apps.subjectassignments.store');
Route::get('/apps/data/subjectassignments/{id}', [SubjectAssignmentController::class, 'show'])->name('apps.subjectassignments.show');
Route::get('/apps/trashed/subjectassignments{id}', [SubjectAssignmentController::class, 'showTrashed'])->name('apps.trashed.subjectassignments.show');
Route::get('/apps/data/subjectassignments/edit/{id}', [SubjectAssignmentController::class, 'edit'])->name('apps.subjectassignments.edit');
Route::get('/apps/trashed/subjectassignments/edit/{id}', [SubjectAssignmentController::class, 'editTrashed'])->name('apps.trashed.subjectassignments.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectassignments/reset/{id}', [SubjectAssignmentController::class, 'reset'])->name('apps.subjectassignments.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/subjectassignments/edit/{id}', [SubjectAssignmentController::class, 'update'])->name('apps.subjectassignments.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/subjectassignments/edit/{id}', [SubjectAssignmentController::class, 'restore'])->name('apps.trashed.subjectassignments.restore');
Route::delete('/apps/data/subjectassignments{id}', [SubjectAssignmentController::class, 'destroy'])->name('apps.subjectassignments.destroy');
Route::delete('/apps/trashed/subjectassignments{id}', [SubjectAssignmentController::class, 'permanentDestroy'])->name('apps.trashed.subjectassignments.destroy');

Route::get('/apps/data/studentassignments', [StudentAssignmentController::class, 'index'])->name('apps.studentassignments.index');
Route::get('/apps/trashed/studentassignments', [StudentAssignmentController::class, 'indexTrashed'])->name('apps.trashed.studentassignments.index');
Route::get('/apps/data/studentassignments/new', [StudentAssignmentController::class, 'create'])->name('apps.studentassignments.create');
Route::post('/apps/data/studentassignments', [StudentAssignmentController::class, 'store'])->name('apps.studentassignments.store');
Route::get('/apps/data/studentassignments/{id}', [StudentAssignmentController::class, 'show'])->name('apps.studentassignments.show');
Route::get('/apps/trashed/studentassignments{id}', [StudentAssignmentController::class, 'showTrashed'])->name('apps.trashed.studentassignments.show');
Route::get('/apps/data/studentassignments/edit/{id}', [StudentAssignmentController::class, 'edit'])->name('apps.studentassignments.edit');
Route::get('/apps/trashed/studentassignments/edit/{id}', [StudentAssignmentController::class, 'editTrashed'])->name('apps.trashed.studentassignments.edit');
Route::match(['PUT', 'PATCH'], '/apps/data/studentassignments/reset/{id}', [StudentAssignmentController::class, 'reset'])->name('apps.studentassignments.reset');
Route::match(['PUT', 'PATCH'], '/apps/data/studentassignments/edit/{id}', [StudentAssignmentController::class, 'update'])->name('apps.studentassignments.update');
Route::match(['PUT', 'PATCH'], '/apps/trashed/studentassignments/edit/{id}', [StudentAssignmentController::class, 'restore'])->name('apps.trashed.studentassignments.restore');
Route::delete('/apps/data/studentassignments{id}', [StudentAssignmentController::class, 'destroy'])->name('apps.studentassignments.destroy');
Route::delete('/apps/trashed/studentassignments{id}', [StudentAssignmentController::class, 'permanentDestroy'])->name('apps.trashed.studentassignments.destroy');