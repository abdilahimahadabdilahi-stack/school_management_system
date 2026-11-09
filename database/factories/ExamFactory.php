<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $examType = $this->faker->randomElement(['midterm', 'final']);
        $examName = $examType === 'midterm' ? 'Midterm Examination' : 'Final Examination';

        return [
            'name' => $examName,
            'subject' => $this->faker->randomElement(array_values(Subject::CORE_SUBJECTS)),
            'exam_type' => $examType,
            'exam_date' => $this->faker->dateTimeBetween('-6 months', '+2 months')->format('Y-m-d'),
            'start_time' => $this->faker->randomElement(['08:00', '09:30', '10:00', '11:30']),
            'total_marks' => $this->faker->randomElement([20, 25, 50, 75, 100]),
        ];
    }
}
