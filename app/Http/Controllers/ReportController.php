<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\SecurityLog;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const REPORTS = [
        'students' => ['label' => 'Students', 'model' => Student::class, 'columns' => ['id', 'name', 'email', 'class_name', 'section']],
        'teachers' => ['label' => 'Teachers', 'model' => Teacher::class, 'columns' => ['id', 'name', 'email']],
        'staff' => ['label' => 'Staff', 'model' => Staff::class, 'columns' => ['id', 'name', 'email']],
        'managers' => ['label' => 'Managers', 'model' => Manager::class, 'columns' => ['id', 'name', 'email']],
        'parents' => ['label' => 'Parents', 'model' => SchoolParent::class, 'columns' => ['id', 'name', 'email', 'phone']],
        'exams' => ['label' => 'Exams', 'model' => Exam::class, 'columns' => ['id', 'name', 'date']],
        'classes' => ['label' => 'Classes', 'model' => SchoolClass::class, 'columns' => ['id', 'name']],
        'payments' => ['label' => 'Payments', 'model' => Payment::class, 'columns' => ['id', 'student_id', 'amount', 'payment_date']],
        'attendance' => ['label' => 'Attendance', 'model' => Attendance::class, 'columns' => ['id', 'student_id', 'attendance_date', 'status']],
        'announcements' => ['label' => 'Announcements', 'model' => Announcement::class, 'columns' => ['id', 'title', 'message', 'created_at']],
        'users' => ['label' => 'Users', 'model' => User::class, 'columns' => ['id', 'name', 'email', 'role']],
        'security-logs' => ['label' => 'Security Logs', 'model' => SecurityLog::class, 'columns' => ['id', 'user_id', 'event_type', 'ip_address', 'created_at']],
    ];

    public function index(): View
    {
        return view('reports.index', ['reports' => self::REPORTS]);
    }

    public function show(string $report, Request $request): View
    {
        abort_unless(isset(self::REPORTS[$report]), 404);

        $definition = self::REPORTS[$report];
        $query = $definition['model']::query();
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            if (in_array('name', $definition['columns'], true)) {
                $query->where('name', 'like', '%'.$search.'%');
            }

            if (in_array('email', $definition['columns'], true)) {
                $query->orWhere('email', 'like', '%'.$search.'%');
            }

            if (in_array('title', $definition['columns'], true)) {
                $query->orWhere('title', 'like', '%'.$search.'%');
            }

            if (in_array('subject', $definition['columns'], true)) {
                $query->orWhere('subject', 'like', '%'.$search.'%');
            }

            if (in_array('class_name', $definition['columns'], true)) {
                $query->orWhere('class_name', 'like', '%'.$search.'%');
            }

            if (in_array('status', $definition['columns'], true)) {
                $query->orWhere('status', 'like', '%'.$search.'%');
            }

            if (in_array('student_id', $definition['columns'], true)) {
                $query->orWhere('student_id', 'like', '%'.$search.'%');
            }
        }

        $records = $query->latest('id')->paginate(25)->withQueryString();

        return view('reports.show', [
            'report' => $definition,
            'records' => $records,
            'search' => $search,
        ]);
    }
}
