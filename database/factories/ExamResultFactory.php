<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
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
        $totalMarks = 100;

        return [
            'exam_id' => Exam::factory(),
            'student_id' => Student::factory(),
            'subject' => $this->faker->randomElement(['Mathematics', 'English', 'Science']),
            'marks_obtained' => $this->faker->randomFloat(2, 0, $totalMarks),
            'total_marks' => $totalMarks,
        ];
    }
}
