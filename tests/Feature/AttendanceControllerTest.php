<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_records_are_filtered_by_class_and_date(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $firstClass = $this->createClass('2A', 2, 'A');
        $secondClass = $this->createClass('2B', 2, 'B');
        $firstStudent = $this->createStudent('Student In 2A', '2A', 'A');
        $secondStudent = $this->createStudent('Student In 2B', '2B', 'B');
        $date = '2026-10-06';

        Attendance::create([
            'student_id' => $firstStudent->id,
            'class_id' => $firstClass->id,
            'attendance_date' => $date,
            'status' => 'present',
        ]);
        Attendance::create([
            'student_id' => $firstStudent->id,
            'class_id' => $firstClass->id,
            'attendance_date' => '2026-10-05',
            'status' => 'absent',
        ]);
        Attendance::create([
            'student_id' => $secondStudent->id,
            'class_id' => $secondClass->id,
            'attendance_date' => $date,
            'status' => 'late',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.index', [
            'date' => $date,
            'class_id' => $firstClass->id,
        ]));

        $response->assertSee('Student In 2A')
            ->assertDontSee('Student In 2B')
            ->assertSee('2A (Section A)')
            ->assertViewHas('attendances', fn (Collection $attendances): bool => $attendances->count() === 1);
    }

    public function test_all_attendance_records_include_their_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $firstClass = $this->createClass('2A', 2, 'A');
        $secondClass = $this->createClass('2B', 2, 'B');
        $firstStudent = $this->createStudent('Student In 2A', '2A', 'A');
        $secondStudent = $this->createStudent('Student In 2B', '2B', 'B');
        $date = '2026-10-06';

        Attendance::create([
            'student_id' => $firstStudent->id,
            'class_id' => $firstClass->id,
            'attendance_date' => $date,
            'status' => 'present',
        ]);
        Attendance::create([
            'student_id' => $secondStudent->id,
            'class_id' => $secondClass->id,
            'attendance_date' => $date,
            'status' => 'late',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.index', ['date' => $date]));

        $response->assertSee('<th>Class</th>', false)
            ->assertSee('Student In 2A')
            ->assertSee('Student In 2B');
    }

    public function test_mark_attendance_requests_class_before_showing_students(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $this->createStudent('Roster Student', '2A', 'A');

        $response = $this->actingAs($user)->get(route('attendance.create', [
            'date' => '2026-10-06',
        ]));

        $response->assertSee('-- Select Class --')
            ->assertDontSee('Roster Student')
            ->assertViewHas('students', fn (Collection $students): bool => $students->isEmpty());
    }

    public function test_mark_attendance_shows_only_students_enrolled_in_selected_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $schoolClass = $this->createClass('2A', 2, 'A');
        $this->createStudent('Student In 2A', '2A', 'A');
        $this->createStudent('Student In 2B', '2B', 'B');

        $response = $this->actingAs($user)->get(route('attendance.create', [
            'date' => '2026-10-06',
            'class_id' => $schoolClass->id,
        ]));

        $response->assertSee('Student In 2A')
            ->assertDontSee('Student In 2B')
            ->assertSee('Attendance for 2A on 2026-10-06');
    }

    public function test_bulk_attendance_is_saved_with_the_selected_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $schoolClass = $this->createClass('2A', 2, 'A');
        $firstStudent = $this->createStudent('Present Student', '2A', 'A');
        $secondStudent = $this->createStudent('Late Student', '2A', 'A');
        $date = '2026-10-06';

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'class_id' => $schoolClass->id,
            'attendance_date' => $date,
            'attendances' => [
                $firstStudent->id => 'present',
                $secondStudent->id => 'late',
            ],
        ]);

        $response->assertRedirect(route('attendance.index', [
            'date' => $date,
            'class_id' => $schoolClass->id,
        ]));
        $this->assertDatabaseHas('attendances', [
            'student_id' => $firstStudent->id,
            'class_id' => $schoolClass->id,
            'attendance_date' => $date,
            'status' => 'present',
        ]);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $secondStudent->id,
            'class_id' => $schoolClass->id,
            'attendance_date' => $date,
            'status' => 'late',
        ]);
    }

    public function test_attendance_cannot_be_saved_without_a_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $student = $this->createStudent('Unassigned Student', '2A', 'A');

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'attendance_date' => '2026-10-06',
            'attendances' => [$student->id => 'present'],
        ]);

        $response->assertSessionHasErrors('class_id');
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_attendance_cannot_be_marked_for_a_student_outside_the_selected_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $schoolClass = $this->createClass('2A', 2, 'A');
        $otherStudent = $this->createStudent('Student In 2B', '2B', 'B');

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'class_id' => $schoolClass->id,
            'attendance_date' => '2026-10-06',
            'attendances' => [$otherStudent->id => 'absent'],
        ]);

        $response->assertSessionHasErrors('attendances');
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_attendance_rejects_statuses_outside_the_supported_set(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $schoolClass = $this->createClass('2A', 2, 'A');
        $student = $this->createStudent('Roster Student', '2A', 'A');

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'class_id' => $schoolClass->id,
            'attendance_date' => '2026-10-06',
            'attendances' => [$student->id => 'excused'],
        ]);

        $response->assertSessionHasErrors('attendances.'.$student->id);
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_attendance_is_unique_per_student_class_and_date(): void
    {
        $schoolClass = $this->createClass('2A', 2, 'A');
        $student = $this->createStudent('Roster Student', '2A', 'A');
        $attributes = [
            'student_id' => $student->id,
            'class_id' => $schoolClass->id,
            'attendance_date' => '2026-10-06',
            'status' => 'present',
        ];
        Attendance::create($attributes);

        $this->expectException(QueryException::class);

        Attendance::create($attributes);
    }

    private function createClass(string $label, int $number, string $section): SchoolClass
    {
        return SchoolClass::create([
            'class_number' => $number,
            'section' => $section,
            'class_label' => $label,
            'capacity' => 40,
        ]);
    }

    private function createStudent(string $name, string $className, string $section): Student
    {
        return Student::factory()->create([
            'name' => $name,
            'class_name' => $className,
            'section' => $section,
        ]);
    }
}
