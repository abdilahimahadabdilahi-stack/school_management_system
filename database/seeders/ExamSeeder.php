<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // Abuur 20 imtixaan oo fake ah
        Exam::factory(20)->create();
    }
}