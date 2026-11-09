<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $sections = ['A', 'B', 'C'];

        for ($classNum = 1; $classNum <= 8; $classNum++) {
            foreach ($sections as $section) {
                SchoolClass::firstOrCreate(
                    ['class_label' => $classNum.$section],
                    [
                        'class_type' => 'primary',
                        'class_number' => $classNum,
                        'section' => $section,
                        'class_label' => $classNum.$section,
                        'class_teacher' => null,
                        'capacity' => 40,
                    ]
                );
            }
        }

        for ($level = 1; $level <= 3; $level++) {
            foreach ($sections as $section) {
                $label = "KG {$level}{$section}";

                SchoolClass::firstOrCreate(
                    ['class_label' => $label],
                    [
                        'class_type' => 'kindergarten',
                        'class_number' => $level,
                        'section' => $section,
                        'class_teacher' => null,
                        'capacity' => 40,
                    ],
                );
            }
        }
    }
}
