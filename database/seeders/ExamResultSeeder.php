<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::query()->orderBy('id')->get();
        $exams = Exam::query()->orderBy('id')->get();
        $subjects = Subject::query()->orderBy('sort_order')->get();
        $percentageBands = [42, 56, 66, 78, 90];

        foreach ($exams as $exam) {
            foreach ($students as $studentIndex => $student) {
                foreach ($subjects as $subjectIndex => $subject) {
                    $percentage = $percentageBands[($studentIndex + $subjectIndex) % count($percentageBands)];

                    ExamResult::updateOrCreate(
                        [
                            'exam_id' => $exam->id,
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'subject' => $subject->name,
                            'marks_obtained' => round($exam->total_marks * $percentage / 100, 2),
                            'total_marks' => $exam->total_marks,
                        ],
                    );
                }
            }
        }
    }
}
