<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\SubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamResultsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SubjectSeeder::class);
    }

    public function test_exam_results_show_breakdown_and_summary_metrics(): void
    {
        $exam = Exam::factory()->create(['subject' => 'Final Exam']);
        $passedStudent = Student::factory()->create(['name' => 'Passed Student']);
        $failedStudent = Student::factory()->create(['name' => 'Failed Student']);
        $mathematics = Subject::where('name', 'Mathematics')->firstOrFail();

        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $passedStudent->id,
            'subject_id' => $mathematics->id,
            'subject' => 'Mathematics',
            'marks_obtained' => 80,
            'total_marks' => 100,
        ]);
        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $failedStudent->id,
            'subject_id' => $mathematics->id,
            'subject' => 'Mathematics',
            'marks_obtained' => 40,
            'total_marks' => 100,
        ]);

        $response = $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('exams.results', $exam));

        $response->assertOk();
        $response->assertSee('Passed Student');
        $response->assertSee('Failed Student');
        $response->assertSee('Total Passed');
        $response->assertSee('80.00%');
        $response->assertSee('Passed');
        $response->assertSee('Failed');
    }

    public function test_exam_results_can_filter_average_students(): void
    {
        $exam = Exam::factory()->create();
        $averageStudent = Student::factory()->create(['name' => 'Average Student']);
        $highStudent = Student::factory()->create(['name' => 'High Student']);
        $english = Subject::where('name', 'English')->firstOrFail();

        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $averageStudent->id,
            'subject_id' => $english->id,
            'subject' => 'English',
            'marks_obtained' => 65,
            'total_marks' => 100,
        ]);
        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $highStudent->id,
            'subject_id' => $english->id,
            'subject' => 'English',
            'marks_obtained' => 90,
            'total_marks' => 100,
        ]);

        $response = $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('exams.results', [$exam, 'status' => 'average']));

        $response->assertOk();
        $response->assertViewHas('filteredSummaries', function ($summaries) use ($averageStudent): bool {
            return $summaries->count() === 1
                && $summaries->first()['student']->is($averageStudent);
        });
    }

    public function test_exam_result_can_be_viewed_updated_and_deleted(): void
    {
        $exam = Exam::factory()->create();
        $student = Student::factory()->create();
        $mathematics = Subject::where('name', 'Mathematics')->firstOrFail();
        $result = ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'subject_id' => $mathematics->id,
            'subject' => 'Mathematics',
            'marks_obtained' => 45,
            'total_marks' => 100,
        ]);
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('exams.results.show', [$exam, $result]))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('45.00');

        $this->actingAs($user)
            ->get(route('exams.results.edit', [$exam, $result]))
            ->assertOk()
            ->assertSee('Edit Exam Result');

        $this->actingAs($user)
            ->put(route('exams.results.update', [$exam, $result]), [
                'student_id' => $student->id,
                'subject' => 'Mathematics',
                'marks_obtained' => 75,
                'total_marks' => 100,
            ])
            ->assertRedirect(route('exams.results', $exam));

        $this->assertDatabaseHas('exam_results', [
            'id' => $result->id,
            'marks_obtained' => 75,
        ]);

        $this->actingAs($user)
            ->delete(route('exams.results.destroy', [$exam, $result]))
            ->assertRedirect(route('exams.results', $exam));

        $this->assertDatabaseMissing('exam_results', ['id' => $result->id]);
    }
}
