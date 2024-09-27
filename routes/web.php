<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use \App\Http\Controllers\TeacherController;
use \App\Http\Controllers\StudentController;
use \App\Http\Controllers\GradeController;
use \App\Http\Controllers\ParentController;
use \App\Http\Controllers\AttendanceController;
use \App\Http\Controllers\SubjectController;
use \App\Http\Controllers\RoleAssignController;

Route::get('/', function () {
    return redirect('/login');
});
require __DIR__.'/auth.php';

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth', 'role:Admin']], function () {

    Route::resource('teacher', TeacherController::class);
    Route::resource('student', StudentController::class);
    Route::resource('classes', GradeController::class);
    Route::resource('parents', ParentController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('subject', SubjectController::class);
    Route::resource('assignrole', RoleAssignController::class);
    Route::get('assign-subject-to-class/{id}', [GradeController::class, 'assignSubject'])->name('class.assign.subject');
    Route::post('assign-subject-to-class/{id}', [GradeController::class, 'storeAssignedSubject'])->name('store.class.assign.subject');
});
