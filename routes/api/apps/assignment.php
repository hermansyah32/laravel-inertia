<?php

use App\Http\API\Apps\Assignment\AssignmentGroupController;
use App\Http\API\Apps\Assignment\StudentAssignmentController;
use App\Http\API\Apps\Assignment\SubjectAssignmentController;
use Illuminate\Support\Facades\Route;

/**============================= Assignment Group ============================================== */
Route::get('assignment/groups', [AssignmentGroupController::class, 'index'])->name('api.assignment.groups.index');
Route::get('trashed/assignment/groups', [AssignmentGroupController::class, 'indexTrashed'])->name('api.trashed.assignment.groups.index');
Route::post('assignment/groups', [AssignmentGroupController::class, 'store'])->name('api.assignment.groups.store');
Route::get('assignment/groups/{id}', [AssignmentGroupController::class, 'show'])->name('api.assignment.groups.show');
Route::get('trashed/assignment/groups/{id}', [AssignmentGroupController::class, 'showTrashed'])->name('api.trashed.assignment.groups.show');
Route::match(['put', 'patch'], 'assignment/groups/{id}', [AssignmentGroupController::class, 'update'])->name('api.assignment.groups.update');
Route::match(['put', 'patch'], 'trashed/assignment/groups/{id}', [AssignmentGroupController::class, 'restore'])->name('api.trashed.assignment.groups.restore');
Route::delete('assignment/groups/{id}', [AssignmentGroupController::class, 'destroy'])->name('api.assignment.groups.destroy');
Route::delete('trashed/assignment/groups/{id}', [AssignmentGroupController::class, 'permanentDestroy'])->name('api.trashed.assignment.groups.destroy');

/**============================= Subject Assignment ============================================== */
Route::get('assignment/subjects', [SubjectAssignmentController::class, 'index'])->name('api.assignment.subjects.index');
Route::get('trashed/assignment/subjects', [SubjectAssignmentController::class, 'indexTrashed'])->name('api.trashed.assignment.subjects.index');
Route::post('assignment/subjects', [SubjectAssignmentController::class, 'store'])->name('api.assignment.subjects.store');
Route::get('assignment/subjects/{id}', [SubjectAssignmentController::class, 'show'])->name('api.assignment.subjects.show');
Route::get('trashed/assignment/subjects/{id}', [SubjectAssignmentController::class, 'showTrashed'])->name('api.trashed.assignment.subjects.show');
Route::match(['put', 'patch'], 'assignment/subjects/{id}', [SubjectAssignmentController::class, 'update'])->name('api.assignment.subjects.update');
Route::match(['put', 'patch'], 'trashed/assignment/subjects/{id}', [SubjectAssignmentController::class, 'restore'])->name('api.trashed.assignment.subjects.restore');
Route::delete('assignment/subjects/{id}', [SubjectAssignmentController::class, 'destroy'])->name('api.assignment.subjects.destroy');
Route::delete('trashed/assignment/subjects/{id}', [SubjectAssignmentController::class, 'permanentDestroy'])->name('api.trashed.assignment.subjects.destroy');

/**============================= Student Assignment ============================================== */
Route::get('assignment/students', [StudentAssignmentController::class, 'index'])->name('api.assignment.students.index');
Route::get('trashed/assignment/students', [StudentAssignmentController::class, 'indexTrashed'])->name('api.trashed.assignment.students.index');
Route::post('assignment/students', [StudentAssignmentController::class, 'store'])->name('api.assignment.students.store');
Route::get('assignment/students/{id}', [StudentAssignmentController::class, 'show'])->name('api.assignment.students.show');
Route::get('trashed/assignment/students/{id}', [StudentAssignmentController::class, 'showTrashed'])->name('api.trashed.assignment.students.show');
Route::match(['put', 'patch'], 'assignment/students/{id}', [StudentAssignmentController::class, 'update'])->name('api.assignment.students.update');
Route::match(['put', 'patch'], 'trashed/assignment/students/{id}', [StudentAssignmentController::class, 'restore'])->name('api.trashed.assignment.students.restore');
Route::delete('assignment/students/{id}', [StudentAssignmentController::class, 'destroy'])->name('api.assignment.students.destroy');
Route::delete('trashed/assignment/students/{id}', [StudentAssignmentController::class, 'permanentDestroy'])->name('api.trashed.assignment.students.destroy');
