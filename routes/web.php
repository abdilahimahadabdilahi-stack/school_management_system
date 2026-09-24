<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
// Models
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Models\Exam;
// Controllers
use App\Models\Manager;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\SecurityLog;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
            'staff_count' => Staff::count(),
            'managers_count' => Manager::count(),
            'parents_count' => SchoolParent::count(),
            'exams_count' => class_exists(Exam::class) ? Exam::count() : 0,
            'classes_count' => SchoolClass::count(),
            'payments_count' => in_array(Auth::user()->role, ['admin', 'manager']) ? Payment::count() : null,
            'reports_count' => 3,
            'recent_logs' => SecurityLog::with('user')->latest()->take(5)->get(),
        ]);
    })->name('dashboard');

    // 1. ROUTES EE LOO OGOL YAHAY TEACHER, MANAGER & ADMIN (Student, Exam, Attendance, Classes & School Setup)
    Route::middleware(['role:admin,manager,teacher'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('exams', ExamController::class);
        Route::get('/exams/{exam}/results', [ExamResultController::class, 'results'])->name('exams.results');
        Route::post('/exams/{exam}/results', [ExamResultController::class, 'storeResult'])->name('exams.results.store');
        Route::get('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'show'])->name('exams.results.show');
        Route::get('/exams/{exam}/results/{examResult}/edit', [ExamResultController::class, 'edit'])->name('exams.results.edit');
        Route::put('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'update'])->name('exams.results.update');
        Route::delete('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'destroy'])->name('exams.results.destroy');
        Route::resource('attendance', AttendanceController::class);
        Route::resource('classes', SchoolClassController::class);
        Route::resource('announcements', AnnouncementController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

        // Routes-ka Cusub ee SCHOOL_DBS Form-ka
        Route::get('/school', function () {
            return view('school');
        })->name('school.index');

        Route::post('/school/store', function (Request $request) {
            try {
                DB::table('students')->insert([
                    'name' => $request->student_name,
                    'class' => $request->class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->with('success', 'Xogta si guul leh ayaa loo kaydiyay Database-ka SCHOOL_DBS!');
            } catch (Exception $e) {
                return back()->with('error', 'Cillad ayaa ka jirtay xidhiidhka DB: '.$e->getMessage());
            }
        })->name('school.store');
    });

    // 2. ROUTES EE LOO OGOL YAHAY ADMIN IYO MANAGER OO KALIYA (Teacher-ka ma geli karo)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('teachers', TeacherController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('managers', ManagerController::class);
        Route::resource('parents', ParentController::class);
        Route::resource('payments', PaymentController::class);

        // Reports Routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    })->name('logout');

});
