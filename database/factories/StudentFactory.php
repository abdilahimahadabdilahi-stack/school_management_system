<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $schoolClass = SchoolClass::query()->inRandomOrder()->first();
        $isKindergarten = $schoolClass?->isKindergarten()
            ?? $this->faker->boolean(20);
        $level = $schoolClass?->class_number
            ?? $this->faker->numberBetween(1, $isKindergarten ? 3 : 8);
        $section = $schoolClass?->section
            ?? $this->faker->randomElement(['A', 'B', 'C']);
        $classLabel = $schoolClass?->class_label
            ?? ($isKindergarten ? "KG {$level}{$section}" : "{$level}{$section}");
        $age = $isKindergarten
            ? $this->faker->numberBetween(3, 6)
            : $this->faker->numberBetween($level + 5, $level + 7);

        return [
            'school_class_id' => null,
            'name' => $this->faker->name(),
            'age' => $age,
            'email' => $this->faker->unique()->safeEmail(),
            'class_name' => $classLabel,
            'section' => $section,
            'subject' => $this->faker->randomElement(array_values(Subject::CORE_SUBJECTS)),
        ];
    }

    public function inSchoolClass(SchoolClass $schoolClass): static
    {
        $age = $schoolClass->isKindergarten()
            ? $this->faker->numberBetween(3, 6)
            : $this->faker->numberBetween($schoolClass->class_number + 5, $schoolClass->class_number + 7);

        return $this->state([
            'school_class_id' => $schoolClass->id,
            'age' => $age,
            'class_name' => $schoolClass->class_label,
            'section' => $schoolClass->section,
        ]);
    }
}
