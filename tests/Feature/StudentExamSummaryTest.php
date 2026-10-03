<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentExamSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_exam_summary_counts_missing_subjects_as_zero(): void
    {
        $exam = Exam::factory()->create([
            'name' => 'Mid-Year Exam',
            'subject' => 'Combined',
            'total_marks' => 100,
        ]);

        $student = Student::factory()->create();

        $mathematics = Subject::query()->firstOrCreate(
            ['name' => 'Mathematics'],
            ['slug' => 'mathematics', 'sort_order' => 1],
        );

        $science = Subject::query()->firstOrCreate(
            ['name' => 'Science'],
            ['slug' => 'science', 'sort_order' => 2],
        );

        ExamResult::query()->create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'subject_id' => $mathematics->id,
            'subject' => $mathematics->name,
            'marks_obtained' => 80,
            'total_marks' => 100,
        ]);

        $summary = $student->examResultsTotals(
            $exam,
            $exam->results()->get(),
            collect([$mathematics, $science])
        );

        $mathematicsRow = $summary['results']->first(fn (array $row): bool => $row['subject']->name === 'Mathematics');
        $scienceRow = $summary['results']->first(fn (array $row): bool => $row['subject']->name === 'Science');

        $this->assertCount(2, $summary['results']);
        $this->assertSame(80.0, $mathematicsRow['marks_obtained']);
        $this->assertSame(0.0, $scienceRow['marks_obtained']);
        $this->assertSame(80.0, $summary['total_obtained']);
        $this->assertSame(200.0, $summary['total_marks']);
        $this->assertSame(40.0, $summary['percentage']);
    }
}
