<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\SchoolClassSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KindergartenIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_kg_student_can_be_enrolled_marked_present_and_given_a_progress_report(): void
    {
        $this->seed(SchoolClassSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = User::factory()->create(['role' => 'teacher']);
        $kgClass = SchoolClass::where('class_label', 'KG 1A')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('students.create'))
            ->assertSee('KG 1A (Section A)');

        $this->actingAs($admin)->post(route('students.store'), [
            'name' => 'Kindergarten Student',
            'age' => 5,
            'school_class_id' => $kgClass->id,
        ])->assertRedirect(route('students.index'));

        $student = Student::where('name', 'Kindergarten Student')->firstOrFail();
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'school_class_id' => $kgClass->id,
            'class_name' => 'KG 1A',
            'section' => 'A',
        ]);

        $this->actingAs($teacher)->post(route('attendance.store'), [
            'class_id' => $kgClass->id,
            'attendance_date' => '2026-11-08',
            'attendances' => [$student->id => 'present'],
        ])->assertRedirect(route('attendance.index', [
            'date' => '2026-11-08',
            'class_id' => $kgClass->id,
        ]));

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'class_id' => $kgClass->id,
            'attendance_date' => '2026-11-08',
            'status' => 'present',
        ]);

        $this->actingAs($teacher)->post(route('kg-evaluations.store'), [
            'class_id' => $kgClass->id,
            'student_id' => $student->id,
            'report_period' => 'Term 1 2026',
            'skill' => 'Communication',
            'rating' => 'Satisfactory',
            'evaluated_on' => '2026-11-08',
            'notes' => 'Communicates confidently with classmates.',
        ])->assertRedirect(route('kg-evaluations.index', [
            'class_id' => $kgClass->id,
            'report_period' => 'Term 1 2026',
        ]));

        $this->assertDatabaseHas('kg_evaluations', [
            'student_id' => $student->id,
            'school_class_id' => $kgClass->id,
            'report_period' => 'Term 1 2026',
            'skill' => 'Communication',
            'rating' => 'Satisfactory',
        ]);

        $this->actingAs($teacher)
            ->get(route('kg-evaluations.index', [
                'class_id' => $kgClass->id,
                'report_period' => 'Term 1 2026',
            ]))
            ->assertSee('Kindergarten Student')
            ->assertSee('Satisfactory')
            ->assertSee('Communicates confidently with classmates.');
    }

    public function test_kg_evaluations_reject_students_from_a_different_class(): void
    {
        $this->seed(SchoolClassSeeder::class);
        $teacher = User::factory()->create(['role' => 'teacher']);
        $firstClass = SchoolClass::where('class_label', 'KG 1A')->firstOrFail();
        $otherClass = SchoolClass::where('class_label', 'KG 1B')->firstOrFail();
        $student = Student::factory()->create([
            'school_class_id' => $firstClass->id,
            'class_name' => $firstClass->class_label,
            'section' => $firstClass->section,
        ]);

        $this->actingAs($teacher)->post(route('kg-evaluations.store'), [
            'class_id' => $otherClass->id,
            'student_id' => $student->id,
            'report_period' => 'Term 1 2026',
            'skill' => 'Communication',
            'rating' => 'Satisfactory',
            'evaluated_on' => '2026-11-08',
        ])->assertSessionHasErrors('student_id');

        $this->assertDatabaseCount('kg_evaluations', 0);
    }

    public function test_kg_sections_are_available_in_attendance_fees_and_exam_results_without_changing_primary_students(): void
    {
        $this->seed(SchoolClassSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $kgClass = SchoolClass::where('class_label', 'KG 2C')->firstOrFail();
        $primaryClass = SchoolClass::where('class_label', '1A')->firstOrFail();
        $primaryStudent = Student::factory()->create([
            'name' => 'Existing Primary Student',
            'class_name' => '1A',
            'section' => 'A',
            'school_class_id' => $primaryClass->id,
        ]);
        $exam = Exam::factory()->create();

        $this->actingAs($admin)
            ->get(route('attendance.create'))
            ->assertSee('KG 2C (Section C)');
        $this->actingAs($admin)
            ->get(route('payments.create'))
            ->assertSee('KG 2C');
        $this->actingAs($admin)
            ->get(route('exams.results', ['exam' => $exam->id, 'class_id' => $kgClass->id]))
            ->assertSee('KG 2C (Section C)')
            ->assertDontSee('Existing Primary Student');

        $this->assertDatabaseHas('students', [
            'id' => $primaryStudent->id,
            'school_class_id' => $primaryClass->id,
            'class_name' => '1A',
            'section' => 'A',
        ]);
        $this->assertSame(9, SchoolClass::where('class_type', 'kindergarten')->count());
        $this->assertSame(24, SchoolClass::where('class_type', 'primary')->count());
    }
}
