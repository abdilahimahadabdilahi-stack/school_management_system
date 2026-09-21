<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Student;
use App\Notifications\AbsenceAlert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class AttendanceAbsenceNotifier
{
    public function check(Student $student, string $attendanceDate): void
    {
        if (! $student->parent_id) {
            return;
        }

        $absentDates = Attendance::query()
            ->where('student_id', $student->id)
            ->where('status', 'absent')
            ->whereDate('attendance_date', '<=', $attendanceDate)
            ->pluck('attendance_date')
            ->map(fn ($date): string => Carbon::parse($date)->toDateString())
            ->unique()
            ->sortDesc()
            ->values();

        if ($absentDates->count() < 3) {
            return;
        }

        $latestAbsentDate = Carbon::parse($absentDates->first());
        $streak = [$latestAbsentDate->toDateString()];

        foreach ($absentDates->skip(1) as $date) {
            $expectedDate = $latestAbsentDate->copy()->subDay()->toDateString();

            if ($date !== $expectedDate) {
                break;
            }

            $latestAbsentDate = Carbon::parse($date);
            $streak[] = $date;
        }

        if (count($streak) < 3) {
            return;
        }

        $message = 'Your child has been absent from school for three consecutive days.';
        $endDate = $streak[0];

        if (Announcement::query()
            ->where('student_id', $student->id)
            ->whereDate('attendance_date', $endDate)
            ->where('message', $message)
            ->exists()) {
            return;
        }

        Announcement::create([
            'parent_id' => $student->parent_id,
            'student_id' => $student->id,
            'attendance_date' => $endDate,
            'title' => 'Three-Day Absence Alert',
            'message' => $message,
        ]);

        Notification::route('mail', $student->parent->email)
            ->notify(new AbsenceAlert($student));
    }
}
