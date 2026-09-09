<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Administrator', 'Accountant', 'Librarian', 'IT Support', 'Receptionist']),
            'salary' => fake()->randomFloat(2, 300, 1500),
            'phone' => fake()->phoneNumber(),
        ];
    }
}