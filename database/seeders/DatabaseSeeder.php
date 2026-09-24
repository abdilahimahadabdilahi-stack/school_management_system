<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SchoolClassSeeder::class);

        foreach ([
            ['name' => 'System Admin', 'email' => 'admin@school.test', 'role' => 'admin'],
            ['name' => 'School Manager', 'email' => 'manager@school.test', 'role' => 'manager'],
            ['name' => 'Lead Teacher', 'email' => 'teacher@school.test', 'role' => 'teacher'],
        ] as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );
        }

        $parents = collect();
        for ($parentNumber = 1; $parentNumber <= 16; $parentNumber++) {
            $parents->push(SchoolParent::updateOrCreate(
                ['email' => "parent{$parentNumber}@school.test"],
                [
                    'name' => fake()->name(),
                    'phone' => fake()->numerify('+252 61 ### ####'),
                    'occupation' => fake()->randomElement(['Teacher', 'Business owner', 'Engineer', 'Doctor']),
                    'address' => fake()->address(),
                ],
            ));
        }

        $classes = SchoolClass::query()->orderBy('class_number')->orderBy('section')->get();
        $students = collect();
        for ($studentNumber = 1; $studentNumber <= 32; $studentNumber++) {
            $schoolClass = $classes[($studentNumber - 1) % $classes->count()];

            $students->push(Student::factory()->create([
                'parent_id' => $parents[($studentNumber - 1) % $parents->count()]->id,
                'age' => fake()->numberBetween(10, 18),
                'class_name' => $schoolClass->class_label,
                'section' => $schoolClass->section,
            ]));
        }

        Teacher::factory(10)->create();
        Staff::factory(10)->create();
        Manager::factory(10)->create();
        Exam::factory(30)->create();
        $this->call(SubjectSeeder::class);
        $this->call(ExamResultSeeder::class);

        foreach ($students as $student) {
            for ($daysAgo = 9; $daysAgo >= 0; $daysAgo--) {
                Attendance::create([
                    'student_id' => $student->id,
                    'attendance_date' => now()->subDays($daysAgo)->toDateString(),
                    'status' => $student->id === $students->first()->id && $daysAgo <= 2
                        ? 'absent'
                        : fake()->randomElement(['present', 'present', 'present', 'late', 'absent']),
                ]);
            }

            $totalFee = 500;
            $amount = fake()->randomElement([250, 350, 500]);
            Payment::create([
                'student_id' => $student->id,
                'school_class_id' => $classes->firstWhere('class_label', $student->class_name)?->id,
                'amount' => $amount,
                'total_fee' => $totalFee,
                'balance' => max(0, $totalFee - $amount),
                'status' => $amount === $totalFee ? 'paid' : 'partial',
                'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'mobile_money']),
                'receipt_number' => 'SEED-'.str_pad((string) $student->id, 5, '0', STR_PAD_LEFT),
                'payment_date' => now()->subDays(fake()->numberBetween(0, 30))->toDateString(),
                'description' => 'Demo payment record',
            ]);
        }

        Announcement::create([
            'title' => 'Welcome to the new school term',
            'message' => 'Classes begin at 8:00 AM. Please arrive before the first lesson.',
        ]);

        Announcement::create([
            'parent_id' => $parents->first()->id,
            'student_id' => $students->first()->id,
            'attendance_date' => now()->toDateString(),
            'title' => 'Three-Day Absence Alert',
            'message' => 'Your child has been absent from school for three consecutive days.',
        ]);

        $this->populateSeparateClassTables();
    }

    private function populateSeparateClassTables(): void
    {
        SchoolClass::query()->each(function (SchoolClass $schoolClass): void {
            $tableName = 'class_'.Str::lower(Str::slug($schoolClass->class_label, '_')).'_students';

            if (! Schema::hasTable($tableName)) {
                return;
            }

            Student::query()
                ->where('class_name', $schoolClass->class_label)
                ->each(function (Student $student) use ($schoolClass, $tableName): void {
                    DB::table($tableName)->updateOrInsert(
                        ['source_student_id' => $student->id],
                        [
                            'school_class_id' => $schoolClass->id,
                            'class_label' => $schoolClass->class_label,
                            'class_teacher' => $schoolClass->class_teacher,
                            'capacity' => $schoolClass->capacity,
                            'parent_id' => $student->parent_id,
                            'student_name' => $student->name,
                            'student_age' => $student->age,
                            'student_email' => $student->email,
                            'section' => $student->section,
                            'subject' => $student->subject,
                            'created_at' => $student->created_at,
                            'updated_at' => $student->updated_at,
                        ],
                    );
                });
        });
    }
}
