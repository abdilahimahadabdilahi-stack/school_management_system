<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'age'        => $this->faker->numberBetween(18, 30),
            'email'      => $this->faker->unique()->safeEmail(),
            'class_name' => $this->faker->randomElement(['Class A', 'Class B', 'Class C']),
            'subject'    => $this->faker->randomElement(['Mathematics', 'English', 'Programming', 'Database']),
        ];
    }
}