<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\AttendanceAbsenceNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
        ]);
        $date = $filters['date'] ?? today()->toDateString();
        $classId = $filters['class_id'] ?? null;

        $attendances = Attendance::with(['student', 'schoolClass'])
            ->whereDate('attendance_date', $date)
            ->when($classId, fn (Builder $query): Builder => $query->where('class_id', $classId))
            ->get();
        $classes = SchoolClass::query()
            ->orderBy('class_number')
            ->orderBy('section')
            ->get();

        return view('attendance.index', compact('attendances', 'date', 'classes', 'classId'));
    }

    public function create(Request $request): View
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
        ]);
        $date = $filters['date'] ?? today()->toDateString();
        $classId = $filters['class_id'] ?? null;
        $classes = SchoolClass::query()
            ->orderBy('class_number')
            ->orderBy('section')
            ->get();
        $schoolClass = $classId ? SchoolClass::findOrFail($classId) : null;
        $students = $schoolClass
            ? $schoolClass->studentsForDisplay()->orderBy('name')->get()
            : collect();

        $existingAttendances = $schoolClass
            ? Attendance::query()
                ->where('class_id', $schoolClass->id)
                ->whereDate('attendance_date', $date)
                ->pluck('status', 'student_id')
                ->all()
            : [];

        return view('attendance.create', compact(
            'classes',
            'schoolClass',
            'students',
            'date',
            'existingAttendances',
        ));
    }

    public function store(Request $request, AttendanceAbsenceNotifier $absenceNotifier): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'attendance_date' => ['required', 'date'],
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*' => ['required', Rule::in(['present', 'absent', 'late'])],
        ]);

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);
        $date = $validated['attendance_date'];
        $studentIds = array_keys($validated['attendances']);
        $enrolledStudents = $schoolClass->studentsForDisplay()
            ->whereKey($studentIds)
            ->get()
            ->keyBy('id');

        if ($enrolledStudents->count() !== count($studentIds)) {
            throw ValidationException::withMessages([
                'attendances' => 'Only students enrolled in the selected class can be marked.',
            ]);
        }

        foreach ($validated['attendances'] as $studentId => $status) {
            $student = $enrolledStudents->get($studentId);

            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $schoolClass->id,
                    'attendance_date' => $date,
                ],
                [
                    'status' => $status,
                ]
            );

            $absenceNotifier->check($student, $date);
        }

        return redirect()->route('attendance.index', ['date' => $date, 'class_id' => $schoolClass->id])
            ->with('success', 'Xaadirinta waa la kaydiyay!');
    }

    public function show(int $id): View
    {
        $student = Student::with('attendances.schoolClass')->findOrFail($id);

        $stats = [
            'present' => $student->attendances->where('status', 'present')->count(),
            'late' => $student->attendances->where('status', 'late')->count(),
            'absent' => $student->attendances->where('status', 'absent')->count(),
        ];

        return view('attendance.show', compact('student', 'stats'));
    }

    public function edit(int $id): View
    {
        $attendance = Attendance::with(['student', 'schoolClass'])->findOrFail($id);

        return view('attendance.edit', compact('attendance'));
    }

    public function update(
        Request $request,
        int $id,
        AttendanceAbsenceNotifier $absenceNotifier,
    ): RedirectResponse {
        $request->validate([
            'status' => 'required|in:present,late,absent',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'status' => $request->status,
        ]);
        $absenceNotifier->check($attendance->student, $attendance->attendance_date);

        return redirect()->route('attendance.index', [
            'date' => $attendance->attendance_date,
            'class_id' => $attendance->class_id,
        ])
            ->with('success', 'Xaadirinta ardayda waa la cusbooneysiiyay!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->attendance_date;
        $classId = $attendance->class_id;
        $attendance->delete();

        return redirect()->route('attendance.index', ['date' => $date, 'class_id' => $classId])
            ->with('success', 'Xaadirinta waa la tirtiray!');
    }
}
