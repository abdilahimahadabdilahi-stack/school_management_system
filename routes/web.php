<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
// Models
use App\Http\Controllers\KgEvaluationController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentRankingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UnpaidStudentsController;
// Controllers
use App\Models\Exam;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

Route::get('/', function () {
    return view('home');
})->name('home');

// Protected Routes (LOGIN OO KALIYA AYAA GELI KARA)
Route::middleware(['auth'])->group(function () {

    $dashboard = function () {
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
    };

    Route::get('/dashboard', $dashboard)->name('dashboard');
    Route::get('/admin/dashboard', $dashboard)
        ->middleware('role:admin')
        ->name('admin.dashboard');
    Route::get('/teacher/portal', $dashboard)
        ->middleware('role:teacher')
        ->name('teacher.portal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin-users.store');
    });

    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/students/unpaid', [UnpaidStudentsController::class, 'index'])->name('students.unpaid');
        Route::resource('students', StudentController::class);
        Route::resource('classes', SchoolClassController::class);
    });

    // Shared academic resources for teachers, managers, and admins.
    Route::middleware(['role:admin,manager,teacher'])->group(function () {
        Route::resource('exams', ExamController::class);
        Route::get('/exams/{exam}/results', [ExamResultController::class, 'results'])->name('exams.results');
        Route::post('/exams/{exam}/results', [ExamResultController::class, 'storeResult'])->name('exams.results.store');
        Route::get('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'show'])->name('exams.results.show');
        Route::get('/exams/{exam}/results/{examResult}/edit', [ExamResultController::class, 'edit'])->name('exams.results.edit');
        Route::put('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'update'])->name('exams.results.update');
        Route::delete('/exams/{exam}/results/{examResult}', [ExamResultController::class, 'destroy'])->name('exams.results.destroy');
        Route::resource('attendance', AttendanceController::class);
        Route::get('/kg-evaluations', [KgEvaluationController::class, 'index'])->name('kg-evaluations.index');
        Route::get('/kg-evaluations/create', [KgEvaluationController::class, 'create'])->name('kg-evaluations.create');
        Route::post('/kg-evaluations', [KgEvaluationController::class, 'store'])->name('kg-evaluations.store');
        Route::get('/rankings', [StudentRankingController::class, 'index'])->name('rankings.index');
        Route::resource('announcements', AnnouncementController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

        // Routes-ka Cusub ee SCHOOL_DBS Form-ka
        Route::get('/school', function () {
            return view('school');
        })->name('school.index');

        Route::post('/school/store', function (Request $request) {
            try {
                DB::table('students')->insert([
                    'name' => $request->input('student_name'),
                    'class' => $request->input('class'),
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
        Route::delete('/reports/users/{user}', [ReportController::class, 'destroy'])
            ->name('reports.users.destroy');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    });

});
