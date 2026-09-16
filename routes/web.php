<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Models
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Manager;
use App\Models\Exam;

// Controllers
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ExamController;

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

// Protected Routes (LOGIN OO KALIYA AYAA GELI KARA)
Route::middleware(['auth'])->group(function () {

    // Dashboard: Dhammaan 3-da role (admin, manager, teacher) waad geli karaan
    Route::get('/', function () {
        return view('welcome', [
            'students_count' => Student::count(),
            'teachers_count' => Teacher::count(),
            'staff_count'    => Staff::count(),
            'managers_count' => Manager::count(),
            'exams_count'    => class_exists(Exam::class) ? Exam::count() : 0,
            'reports_count'  => 3,
        ]);
    })->name('dashboard');

    // 1. ROUTES EE LOO OGOL YAHAY TEACHER, MANAGER & ADMIN (Student, Exam, Attendance)
    Route::middleware(['role:admin,manager,teacher'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('exams', ExamController::class);
        Route::resource('attendance', AttendanceController::class);
    });

    // 2. ROUTES EE LOO OGOL YAHAY ADMIN IYO MANAGER OO KALIYA (Teacher-ka ma geli karo)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('teachers', TeacherController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('managers', ManagerController::class);

        // Reports Routes
        Route::get('/reports', function () { return view('reports.index'); })->name('reports.index');
        Route::get('/reports/create', function () { return view('reports.create'); })->name('reports.create');
        Route::get('/reports/{id}', function ($id) { return view('reports.show', compact('id')); })->name('reports.show');
        Route::get('/reports/{id}/edit', function ($id) { return view('reports.edit', compact('id')); })->name('reports.edit');
        Route::put('/reports/{id}', function (Request $request, $id) {
            return redirect()->route('reports.index')->with('success', 'Report updated successfully!');
        })->name('reports.update');
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

});