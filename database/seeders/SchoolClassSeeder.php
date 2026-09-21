<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $sections = ['A', 'B'];

        for ($classNum = 1; $classNum <= 8; $classNum++) {
            foreach ($sections as $section) {
                SchoolClass::firstOrCreate(
                    ['class_label' => $classNum.$section],
                    [
                        'class_number' => $classNum,
                        'section' => $section,
                        'class_label' => $classNum.$section,
                        'class_teacher' => null,
                        'capacity' => 40,
                    ]
                );
            }
        }
    }
}
