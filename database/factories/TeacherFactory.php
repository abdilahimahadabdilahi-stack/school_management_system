<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name(),
            'email'   => $this->faker->unique()->safeEmail(),
            'phone'   => $this->faker->phoneNumber(),
            'subject' => $this->faker->randomElement(['Mathematics', 'English', 'Physics', 'Programming']),
        ];
    }
}