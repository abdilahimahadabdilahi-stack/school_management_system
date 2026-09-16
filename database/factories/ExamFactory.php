<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $subjects = ['Mathematics', 'English', 'Science', 'Islamic Studies', 'Somali', 'History', 'Physics', 'Chemistry'];
        $examTypes = ['Midterm Exam', 'Final Exam', 'Monthly Quiz', 'Unit Test'];

        return [
            'name'        => $this->faker->randomElement($examTypes),
            'subject'     => $this->faker->randomElement($subjects),
            'exam_date'   => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'start_time'  => $this->faker->randomElement(['08:00', '09:30', '10:00', '11:30', '13:00']),
            'total_marks' => $this->faker->randomElement([50, 100]),
        ];
    }
}