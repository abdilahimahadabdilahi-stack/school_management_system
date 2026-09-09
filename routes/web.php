<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    $students_count = \App\Models\Student::count();
    $teachers_count = \App\Models\Teacher::count();
    $staff_count    = \App\Models\Staff::count();

    return view('welcome', compact('students_count', 'teachers_count', 'staff_count'));
});

// Resource Routes
Route::resource('students', StudentController::class);
Route::resource('teachers', TeacherController::class);
Route::resource('staff', StaffController::class);

// Shortcut redirect si /teacher uu u tago /teachers
Route::redirect('/teacher', '/teachers');