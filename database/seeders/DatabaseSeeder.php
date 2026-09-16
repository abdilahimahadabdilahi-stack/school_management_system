<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Manager;
use App\Models\Attendance;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory(30)->create();
        Teacher::factory(10)->create();
        Staff::factory(10)->create();
        Manager::factory(10)->create();
        Exam::factory(30)->create();
    }
}