<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Manager;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seeder_only_creates_school_reference_data(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(33, SchoolClass::query()->count());
        $this->assertSame(7, Subject::core()->count());
        $this->assertSame(0, Student::query()->count());
        $this->assertSame(0, Teacher::query()->count());
        $this->assertSame(0, Manager::query()->count());
        $this->assertSame(0, Staff::query()->count());
        $this->assertSame(0, Exam::query()->count());
        $this->assertSame(0, Attendance::query()->count());
        $this->assertSame(0, ExamResult::query()->count());
    }
}
