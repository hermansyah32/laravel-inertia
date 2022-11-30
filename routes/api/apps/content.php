<?php

use App\Http\API\Apps\Content\SubjectContentController;
use App\Http\API\Apps\Content\SubjectController;
use App\Http\API\Apps\Content\SubjectGroupController;
use App\Http\API\Apps\Content\SubjectReferenceController;
use Illuminate\Support\Facades\Route;

/**============================= Subject ============================================== */
Route::get('subject', [SubjectController::class, 'index'])->name('api.subject.index');
Route::get('trashed/subject', [SubjectController::class, 'indexTrashed'])->name('api.trashed.subject.index');
Route::post('subject', [SubjectController::class, 'store'])->name('api.subject.store');
Route::get('subject/{id}', [SubjectController::class, 'show'])->name('api.subject.show');
Route::get('trashed/subject/{id}', [SubjectController::class, 'showTrashed'])->name('api.trashed.subject.show');
Route::match(['put', 'patch'], 'subject/{id}', [SubjectController::class, 'update'])->name('api.subject.update');
Route::match(['put', 'patch'], 'trashed/subject/{id}', [SubjectController::class, 'restore'])->name('api.trashed.subject.restore');
Route::delete('subject/{id}', [SubjectController::class, 'destroy'])->name('api.subject.destroy');
Route::delete('trashed/subject/{id}', [SubjectController::class, 'permanentDestroy'])->name('api.trashed.subject.destroy');

/**============================= Subject Group ============================================== */
Route::get('subject/groups', [SubjectGroupController::class, 'index'])->name('api.subject.groups.index');
Route::get('trashed/subject/groups', [SubjectGroupController::class, 'indexTrashed'])->name('api.trashed.subject.groups.index');
Route::post('subject/groups', [SubjectGroupController::class, 'store'])->name('api.subject.groups.store');
Route::get('subject/groups/{id}', [SubjectGroupController::class, 'show'])->name('api.subject.groups.show');
Route::get('trashed/subject/groups/{id}', [SubjectGroupController::class, 'showTrashed'])->name('api.trashed.subject.groups.show');
Route::match(['put', 'patch'], 'subject/groups/{id}', [SubjectGroupController::class, 'update'])->name('api.subject.groups.update');
Route::match(['put', 'patch'], 'trashed/subject/groups/{id}', [SubjectGroupController::class, 'restore'])->name('api.trashed.subject.groups.restore');
Route::delete('subject/groups/{id}', [SubjectGroupController::class, 'destroy'])->name('api.subject.groups.destroy');
Route::delete('trashed/subject/groups/{id}', [SubjectGroupController::class, 'permanentDestroy'])->name('api.trashed.subject.groups.destroy');

/**============================= Subject Content ============================================== */
Route::get('subject/contents', [SubjectContentController::class, 'index'])->name('api.subject.contents.index');
Route::get('trashed/subject/contents', [SubjectContentController::class, 'indexTrashed'])->name('api.trashed.subject.contents.index');
Route::post('subject/contents', [SubjectContentController::class, 'store'])->name('api.subject.contents.store');
Route::get('subject/contents/{id}', [SubjectContentController::class, 'show'])->name('api.subject.contents.show');
Route::get('trashed/subject/contents/{id}', [SubjectContentController::class, 'showTrashed'])->name('api.trashed.subject.contents.show');
Route::match(['put', 'patch'], 'subject/contents/{id}', [SubjectContentController::class, 'update'])->name('api.subject.contents.update');
Route::match(['put', 'patch'], 'trashed/subject/contents/{id}', [SubjectContentController::class, 'restore'])->name('api.trashed.subject.contents.restore');
Route::delete('subject/contents/{id}', [SubjectContentController::class, 'destroy'])->name('api.subject.contents.destroy');
Route::delete('trashed/subject/contents/{id}', [SubjectContentController::class, 'permanentDestroy'])->name('api.trashed.subject.contents.destroy');

/**============================= Subject Reference ============================================== */
Route::get('subject/references', [SubjectReferenceController::class, 'index'])->name('api.subject.references.index');
Route::get('trashed/subject/references', [SubjectReferenceController::class, 'indexTrashed'])->name('api.trashed.subject.references.index');
Route::post('subject/references', [SubjectReferenceController::class, 'store'])->name('api.subject.references.store');
Route::get('subject/references/{id}', [SubjectReferenceController::class, 'show'])->name('api.subject.references.show');
Route::get('trashed/subject/references/{id}', [SubjectReferenceController::class, 'showTrashed'])->name('api.trashed.subject.references.show');
Route::match(['put', 'patch'], 'subject/references/{id}', [SubjectReferenceController::class, 'update'])->name('api.subject.references.update');
Route::match(['put', 'patch'], 'trashed/subject/references/{id}', [SubjectReferenceController::class, 'restore'])->name('api.trashed.subject.references.restore');
Route::delete('subject/references/{id}', [SubjectReferenceController::class, 'destroy'])->name('api.subject.references.destroy');
Route::delete('trashed/subject/references/{id}', [SubjectReferenceController::class, 'permanentDestroy'])->name('api.trashed.subject.references.destroy');
