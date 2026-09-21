<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\SchoolParent;
use App\Models\Student;
use App\Notifications\AbsenceAlert;
use App\Services\AttendanceAbsenceNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AttendanceAbsenceNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_receives_an_alert_after_three_consecutive_absences(): void
    {
        Notification::fake();
        $parent = SchoolParent::create([
            'name' => 'Amina Hassan',
            'email' => 'amina@example.com',
        ]);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        foreach ([
            ['student_id' => $student->id, 'attendance_date' => '2026-09-18', 'status' => 'absent'],
            ['student_id' => $student->id, 'attendance_date' => '2026-09-19', 'status' => 'absent'],
            ['student_id' => $student->id, 'attendance_date' => '2026-09-20', 'status' => 'absent'],
        ] as $attendance) {
            Attendance::create($attendance);
        }

        app(AttendanceAbsenceNotifier::class)->check($student, '2026-09-20');

        $this->assertDatabaseHas('announcements', [
            'student_id' => $student->id,
            'parent_id' => $parent->id,
            'message' => 'Your child has been absent from school for three consecutive days.',
        ]);
        Notification::assertSentOnDemand(AbsenceAlert::class, function (AbsenceAlert $notification, array $channels, object $notifiable): bool {
            return $channels === ['mail'] && $notifiable->routes['mail'] === 'amina@example.com';
        });
    }

    public function test_a_second_check_does_not_duplicate_the_same_alert(): void
    {
        Notification::fake();
        $parent = SchoolParent::create([
            'name' => 'Amina Hassan',
            'email' => 'amina@example.com',
        ]);
        $student = Student::factory()->create(['parent_id' => $parent->id]);
        foreach ([
            ['student_id' => $student->id, 'attendance_date' => '2026-09-18', 'status' => 'absent'],
            ['student_id' => $student->id, 'attendance_date' => '2026-09-19', 'status' => 'absent'],
            ['student_id' => $student->id, 'attendance_date' => '2026-09-20', 'status' => 'absent'],
        ] as $attendance) {
            Attendance::create($attendance);
        }
        $notifier = app(AttendanceAbsenceNotifier::class);

        $notifier->check($student, '2026-09-20');
        $notifier->check($student, '2026-09-20');

        $this->assertSame(1, Announcement::count());
        Notification::assertSentOnDemandTimes(AbsenceAlert::class, 1);
    }
}
