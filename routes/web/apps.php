<?php

use App\Http\Controllers\Apps\Class\StudentGradeController;
use Illuminate\Support\Facades\Route;

// default apps route
Route::get('/apps', function () {
    return redirect(route('apps.grade'));
})->name('apps');

Route::get('/apps/grade', [StudentGradeController::class, 'index'])->name('apps.grade');
