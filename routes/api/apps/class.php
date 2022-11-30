<?php

use App\Http\API\Apps\Class\StudentClassController;
use App\Http\API\Apps\Class\StudentDepartmentController;
use App\Http\API\Apps\Class\StudentGradeController;
use Illuminate\Support\Facades\Route;

/**============================= Student Department ============================================== */
Route::get('departments', [StudentDepartmentController::class, 'index'])->name('api.departments.index');
Route::get('trashed/departments', [StudentDepartmentController::class, 'indexTrashed'])->name('api.trashed.departments.index');
Route::post('departments', [StudentDepartmentController::class, 'store'])->name('api.departments.store');
Route::get('departments/{id}', [StudentDepartmentController::class, 'show'])->name('api.departments.show');
Route::get('trashed/departments/{id}', [StudentDepartmentController::class, 'showTrashed'])->name('api.trashed.departments.show');
Route::match(['put', 'patch'], 'departments/{id}', [StudentDepartmentController::class, 'update'])->name('api.departments.update');
Route::match(['put', 'patch'], 'trashed/departments/{id}', [StudentDepartmentController::class, 'restore'])->name('api.trashed.departments.restore');
Route::delete('departments/{id}', [StudentDepartmentController::class, 'destroy'])->name('api.departments.destroy');
Route::delete('trashed/departments/{id}', [StudentDepartmentController::class, 'permanentDestroy'])->name('api.trashed.departments.destroy');

/**============================= Student Grade ============================================== */
Route::get('grades', [StudentGradeController::class, 'index'])->name('api.grades.index');
Route::get('trashed/grades', [StudentGradeController::class, 'indexTrashed'])->name('api.trashed.grades.index');
Route::post('grades', [StudentGradeController::class, 'store'])->name('api.grades.store');
Route::get('grades/{id}', [StudentGradeController::class, 'show'])->name('api.grades.show');
Route::get('trashed/grades/{id}', [StudentGradeController::class, 'showTrashed'])->name('api.trashed.grades.show');
Route::match(['put', 'patch'], 'grades/{id}', [StudentGradeController::class, 'update'])->name('api.grades.update');
Route::match(['put', 'patch'], 'trashed/grades/{id}', [StudentGradeController::class, 'restore'])->name('api.trashed.grades.restore');
Route::delete('grades/{id}', [StudentGradeController::class, 'destroy'])->name('api.grades.destroy');
Route::delete('trashed/grades/{id}', [StudentGradeController::class, 'permanentDestroy'])->name('api.trashed.grades.destroy');

/**============================= Student Class ============================================== */
Route::get('classes', [StudentClassController::class, 'index'])->name('api.classes.index');
Route::get('trashed/classes', [StudentClassController::class, 'indexTrashed'])->name('api.trashed.classes.index');
Route::post('classes', [StudentClassController::class, 'store'])->name('api.classes.store');
Route::get('classes/{id}', [StudentClassController::class, 'show'])->name('api.classes.show');
Route::get('trashed/classes/{id}', [StudentClassController::class, 'showTrashed'])->name('api.trashed.classes.show');
Route::match(['put', 'patch'], 'classes/{id}', [StudentClassController::class, 'update'])->name('api.classes.update');
Route::match(['put', 'patch'], 'trashed/classes/{id}', [StudentClassController::class, 'restore'])->name('api.trashed.classes.restore');
Route::delete('classes/{id}', [StudentClassController::class, 'destroy'])->name('api.classes.destroy');
Route::delete('trashed/classes/{id}', [StudentClassController::class, 'permanentDestroy'])->name('api.trashed.classes.destroy');
