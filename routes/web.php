<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;

// 1. Dashboard Page
Route::get('/', function () {
    return view('welcome', [
        'students_count' => Student::count(),
        'teachers_count' => Teacher::count(),
        'staff_count'    => Staff::count(),
    ]);
})->name('dashboard');

// 2. Resource Routes (Students, Teachers, Staff, Attendance)
Route::resource('students', App\Http\Controllers\StudentController::class)->names('students');
Route::resource('teachers', App\Http\Controllers\TeacherController::class)->names('teachers');
Route::resource('staff', App\Http\Controllers\StaffController::class)->names('staff');
Route::resource('attendance', App\Http\Controllers\AttendanceController::class)->names('attendance');

// 3. Logout Route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');