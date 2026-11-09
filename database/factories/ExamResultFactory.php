<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamResult>
 */
class ExamResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalMarks = $this->faker->randomElement([20, 25, 50, 75, 100]);
        $subject = $this->faker->randomElement(array_values(Subject::CORE_SUBJECTS));
        $percentage = $this->faker->randomFloat(2, 35, 100);

        return [
            'exam_id' => Exam::factory(),
            'student_id' => Student::factory(),
            'subject' => $subject,
            'marks_obtained' => round($totalMarks * $percentage / 100, 2),
            'total_marks' => $totalMarks,
        ];
    }
}
