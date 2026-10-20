<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\SecurityLog;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PopulateDemoDataSeeder extends Seeder
{
    private const MINIMUM_RECORDS = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            SchoolClassSeeder::class,
            SubjectSeeder::class,
        ]);

        User::factory()->count($this->recordsNeeded('users'))->create();
        $this->seedParents();

        $classes = SchoolClass::query()
            ->orderBy('class_number')
            ->orderBy('section')
            ->get();
        $parents = SchoolParent::query()->orderBy('id')->limit(self::MINIMUM_RECORDS)->get();

        $this->seedStudents($classes, $parents);

        Teacher::factory()->count($this->recordsNeeded('teachers'))->create();
        Staff::factory()->count($this->recordsNeeded('staff'))->create();
        Manager::factory()->count($this->recordsNeeded('managers'))->create();
        Exam::factory()->count($this->recordsNeeded('exams'))->create();

        $students = Student::query()->orderBy('id')->limit(self::MINIMUM_RECORDS)->get();
        $this->seedExamResults($students);
        $this->seedAttendances($students, $classes);
        $this->seedPayments($students, $classes);
        $this->seedAnnouncements($students, $parents);
        $this->seedSecurityLogs();
    }

    private function recordsNeeded(string $table): int
    {
        return max(0, self::MINIMUM_RECORDS - DB::table($table)->count());
    }

    private function seedParents(): void
    {
        $faker = fake();
        $recordsNeeded = $this->recordsNeeded('parents');

        for ($index = 0; $index < $recordsNeeded; $index++) {
            SchoolParent::query()->create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'occupation' => $faker->jobTitle(),
                'address' => $faker->address(),
            ]);
        }
    }

    /**
     * @param  Collection<int, SchoolClass>  $classes
     * @param  Collection<int, SchoolParent>  $parents
     */
    private function seedStudents(Collection $classes, Collection $parents): void
    {
        $recordsNeeded = $this->recordsNeeded('students');

        for ($index = 0; $index < $recordsNeeded; $index++) {
            $schoolClass = $classes[$index % $classes->count()];
            $parent = $parents[$index % $parents->count()];

            Student::factory()->create([
                'parent_id' => $parent->id,
                'age' => fake()->numberBetween(10, 18),
                'class_name' => $schoolClass->class_label,
                'section' => $schoolClass->section,
                'subject' => Subject::query()->inRandomOrder()->value('name'),
            ]);
        }
    }

    /**
     * @param  Collection<int, Student>  $students
     */
    private function seedExamResults(Collection $students): void
    {
        $recordsNeeded = $this->recordsNeeded('exam_results');
        if ($recordsNeeded === 0) {
            return;
        }

        $exams = Exam::query()->orderBy('id')->limit(self::MINIMUM_RECORDS)->get();
        $subjects = Subject::query()->orderBy('sort_order')->get();
        $existingResults = ExamResult::query()
            ->get(['exam_id', 'student_id', 'subject'])
            ->mapWithKeys(fn (ExamResult $result): array => [
                $this->examResultKey($result->exam_id, $result->student_id, $result->subject) => true,
            ])
            ->all();

        foreach ($exams as $exam) {
            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    $key = $this->examResultKey($exam->id, $student->id, $subject->name);
                    if (isset($existingResults[$key])) {
                        continue;
                    }

                    $totalMarks = (float) $exam->total_marks;
                    ExamResult::query()->create([
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'subject' => $subject->name,
                        'marks_obtained' => fake()->randomFloat(2, 0, $totalMarks),
                        'total_marks' => $totalMarks,
                    ]);

                    $existingResults[$key] = true;
                    $recordsNeeded--;
                    if ($recordsNeeded === 0) {
                        return;
                    }
                }
            }
        }
    }

    /**
     * @param  Collection<int, Student>  $students
     * @param  Collection<int, SchoolClass>  $classes
     */
    private function seedAttendances(Collection $students, Collection $classes): void
    {
        $recordsNeeded = $this->recordsNeeded('attendances');
        if ($recordsNeeded === 0) {
            return;
        }

        $classIds = $classes->keyBy('class_label');
        $existingAttendances = Attendance::query()
            ->get(['student_id', 'class_id', 'attendance_date'])
            ->mapWithKeys(fn (Attendance $attendance): array => [
                $this->attendanceKey(
                    $attendance->student_id,
                    $attendance->class_id,
                    (string) $attendance->attendance_date,
                ) => true,
            ])
            ->all();

        for ($daysAgo = 0; $recordsNeeded > 0; $daysAgo++) {
            foreach ($students as $student) {
                $schoolClass = $classIds->get($student->class_name);
                if ($schoolClass === null) {
                    continue;
                }

                $attendanceDate = now()->subDays($daysAgo)->toDateString();
                $key = $this->attendanceKey($student->id, $schoolClass->id, $attendanceDate);
                if (isset($existingAttendances[$key])) {
                    continue;
                }

                Attendance::query()->create([
                    'student_id' => $student->id,
                    'class_id' => $schoolClass->id,
                    'attendance_date' => $attendanceDate,
                    'status' => fake()->randomElement(['present', 'present', 'late', 'absent']),
                ]);

                $existingAttendances[$key] = true;
                $recordsNeeded--;
                if ($recordsNeeded === 0) {
                    return;
                }
            }
        }
    }

    /**
     * @param  Collection<int, Student>  $students
     * @param  Collection<int, SchoolClass>  $classes
     */
    private function seedPayments(Collection $students, Collection $classes): void
    {
        $recordsNeeded = $this->recordsNeeded('payments');
        $classIds = $classes->keyBy('class_label');

        for ($index = 0; $index < $recordsNeeded; $index++) {
            $student = $students[$index % $students->count()];
            $amount = fake()->randomElement([250, 350, 500]);
            $totalFee = 500;

            Payment::query()->create([
                'student_id' => $student->id,
                'school_class_id' => $classIds->get($student->class_name)?->id,
                'amount' => $amount,
                'total_fee' => $totalFee,
                'balance' => max(0, $totalFee - $amount),
                'status' => $amount === $totalFee ? 'paid' : 'partial',
                'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'mobile_money']),
                'receipt_number' => 'DEMO-'.Str::uuid(),
                'payment_date' => now()->subDays(fake()->numberBetween(0, 90))->toDateString(),
                'description' => 'Generated demo payment',
            ]);
        }
    }

    /**
     * @param  Collection<int, Student>  $students
     * @param  Collection<int, SchoolParent>  $parents
     */
    private function seedAnnouncements(Collection $students, Collection $parents): void
    {
        $recordsNeeded = $this->recordsNeeded('announcements');

        for ($index = 0; $index < $recordsNeeded; $index++) {
            $student = $students[$index % $students->count()];

            Announcement::query()->create([
                'parent_id' => $student->parent_id ?? $parents[$index % $parents->count()]->id,
                'student_id' => $student->id,
                'attendance_date' => now()->subDays(fake()->numberBetween(0, 30))->toDateString(),
                'title' => fake()->sentence(4),
                'message' => fake()->paragraph(),
                'read_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            ]);
        }
    }

    private function seedSecurityLogs(): void
    {
        $recordsNeeded = $this->recordsNeeded('security_logs');
        $users = User::query()->orderBy('id')->limit(self::MINIMUM_RECORDS)->get();

        for ($index = 0; $index < $recordsNeeded; $index++) {
            SecurityLog::query()->create([
                'user_id' => $users[$index % $users->count()]->id,
                'event_type' => fake()->randomElement([
                    'login.success',
                    'login.failed',
                    'password.updated',
                    'profile.updated',
                ]),
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'details' => fake()->sentence(),
            ]);
        }
    }

    private function examResultKey(int $examId, int $studentId, string $subject): string
    {
        return "{$examId}:{$studentId}:{$subject}";
    }

    private function attendanceKey(int $studentId, int $classId, string $attendanceDate): string
    {
        return "{$studentId}:{$classId}:{$attendanceDate}";
    }
}
