<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Services\AttendanceAbsenceNotifier;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // List dhan oo xaadirinta taariikh kasta ah
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $attendances = Attendance::with('student')
            ->where('attendance_date', $date)
            ->get();

        return view('attendance.index', compact('attendances', 'date'));
    }

    // Foomka xaadirinta cusub ama taariikh gaar ah
    public function create(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $students = Student::all();

        // Xaadirintii hore ee taariikhdan ka jirtay
        $existingAttendances = Attendance::where('attendance_date', $date)
            ->pluck('status', 'student_id')
            ->toArray();

        return view('attendance.create', compact('students', 'date', 'existingAttendances'));
    }

    // Kaydinta ama update-ka xaadirinta badanaaba
    public function store(Request $request, AttendanceAbsenceNotifier $absenceNotifier)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
        ]);

        $date = $request->attendance_date;

        foreach ($request->attendances as $student_id => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'attendance_date' => $date,
                ],
                [
                    'status' => $status,
                ]
            );

            $absenceNotifier->check(Student::findOrFail($student_id), $date);
        }

        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', 'Xaadirinta waa la kaydiyay!');
    }

    // Arday gaar ah oo la eego taariikhdiisa xaadirinta
    public function show($id)
    {
        $student = Student::with('attendances')->findOrFail($id);

        $stats = [
            'present' => $student->attendances->where('status', 'present')->count(),
            'late' => $student->attendances->where('status', 'late')->count(),
            'absent' => $student->attendances->where('status', 'absent')->count(),
        ];

        return view('attendance.show', compact('student', 'stats'));
    }

    // Wax ka beddelka (Edit) xaadirin record gaar ah
    public function edit($id)
    {
        $attendance = Attendance::with('student')->findOrFail($id);

        return view('attendance.edit', compact('attendance'));
    }

    // Update-ka record gaar ah
    public function update(Request $request, $id, AttendanceAbsenceNotifier $absenceNotifier)
    {
        $request->validate([
            'status' => 'required|in:present,late,absent',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'status' => $request->status,
        ]);
        $absenceNotifier->check($attendance->student, $attendance->attendance_date);

        return redirect()->route('attendance.index', ['date' => $attendance->attendance_date])
            ->with('success', 'Xaadirinta ardayda waa la cusbooneysiiyay!');
    }

    // Delete record gaar ah
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->attendance_date;
        $attendance->delete();

        return redirect()->route('attendance.index', ['date' => $date])
            ->with('success', 'Xaadirinta waa la tirtiray!');
    }
}
