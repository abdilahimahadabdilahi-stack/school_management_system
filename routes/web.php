<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;

// Auth Routes (Login, Register, Password Reset)
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

// Protected Routes (LOGIN OO KALIYA AYAA GELI KARA)
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('welcome', [
            'students_count' => Student::count(),
            'teachers_count' => Teacher::count(),
            'staff_count'    => Staff::count(),
        ]);
    })->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('attendance', AttendanceController::class);

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});