<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboards_use_cumulative_percentages_scope_by_class_and_keep_cutoff_ties(): void
    {
        $classA = SchoolClass::create([
            'class_number' => 1,
            'section' => 'A',
            'class_label' => '1A',
            'capacity' => 40,
        ]);
        $classB = SchoolClass::create([
            'class_number' => 1,
            'section' => 'B',
            'class_label' => '1B',
            'capacity' => 40,
        ]);
        $exam = Exam::factory()->create();

        foreach (range(1, 9) as $studentNumber) {
            $this->recordResult($classA, $exam, "High Student {$studentNumber}", 90, 100);
        }

        $this->recordResult($classA, $exam, 'Cutoff Student A', 80, 100);
        $this->recordResult($classA, $exam, 'Cutoff Student B', 80, 100);
        $this->recordResult($classA, $exam, 'Outside Top Ten', 70, 100);

        $cumulativeStudent = $this->recordResult($classB, $exam, 'Cumulative Leader', 50, 50);
        $secondExam = Exam::factory()->create();
        ExamResult::create([
            'exam_id' => $secondExam->id,
            'student_id' => $cumulativeStudent->id,
            'subject' => 'Mathematics',
            'marks_obtained' => 50,
            'total_marks' => 150,
        ]);

        $user = User::factory()->create(['role' => 'teacher']);
        $classAResponse = $this->actingAs($user)
            ->get(route('rankings.index', ['class_id' => $classA->id]));

        $classAResponse->assertViewHas('classTopStudents', function ($students): bool {
            return $students->count() === 11
                && $students->where('rank', 10)->count() === 2
                && $students->every(fn (object $student): bool => $student->class_name === '1A')
                && $students->doesntContain(fn (object $student): bool => $student->name === 'Outside Top Ten');
        });
        $classAResponse->assertViewHas('overallTopStudents', function ($students): bool {
            return $students->first()->name === 'High Student 1';
        });

        $classBResponse = $this->actingAs($user)
            ->get(route('rankings.index', ['class_id' => $classB->id]));

        $classBResponse->assertViewHas('classTopStudents', function ($students) use ($cumulativeStudent): bool {
            $leader = $students->first();

            return $students->count() === 1
                && $leader->id === $cumulativeStudent->id
                && (float) $leader->total_obtained === 100.0
                && (float) $leader->total_possible_marks === 200.0
                && (float) $leader->percentage === 50.0;
        });
    }

    private function recordResult(SchoolClass $schoolClass, Exam $exam, string $name, float $marks, float $total): Student
    {
        $student = Student::factory()->create([
            'name' => $name,
            'class_name' => $schoolClass->class_label,
            'section' => $schoolClass->section,
        ]);

        ExamResult::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'subject' => 'Mathematics',
            'marks_obtained' => $marks,
            'total_marks' => $total,
        ]);

        return $student;
    }
}
