<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use \App\Http\Controllers\TeacherController;
use \App\Http\Controllers\StudentController;
use \App\Http\Controllers\ClassController;
use \App\Http\Controllers\ParentController;
use \App\Http\Controllers\SubjectController;
use \App\Http\Controllers\TimetableController;

Route::get('/', function () {
    return redirect('/login');
});
require __DIR__.'/auth.php';

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth', 'role:Admin']], function () {

    Route::resource('teacher', TeacherController::class);
    Route::resource('student', StudentController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('parents', ParentController::class);
    Route::resource('subject', SubjectController::class);
});
Route::get('classes/{class}/timetable', [TimetableController::class, 'show'])->name('timetable.show');
Route::get('/get-teachers/{subject}', [TimetableController::class, 'getTeachers']);
Route::put('classes/{class}/timetable', [TimetableController::class, 'update'])->name('timetable.update');
Route::delete('classes/{class}/timetable', [TimetableController::class, 'destroy'])->name('timetable.destroy');
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
