<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory(30)->create();
        Teacher::factory(10)->create();
        Staff::factory(10)->create();
      
    }
}