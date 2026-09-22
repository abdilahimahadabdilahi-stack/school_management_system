<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_index_can_filter_by_name(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Student::factory()->create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'class_name' => 'Grade 7',
        ]);

        Student::factory()->create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'class_name' => 'Grade 8',
        ]);

        $response = $this->actingAs($user)
            ->get(route('students.index', ['search' => 'Alice']));

        $response->assertOk();
        $response->assertSee('Alice Johnson');
        $response->assertDontSee('Bob Smith');
    }

    public function test_exams_index_can_filter_by_name_or_subject(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Exam::factory()->create([
            'name' => 'Midterm Exam',
            'subject' => 'Mathematics',
        ]);

        Exam::factory()->create([
            'name' => 'Final Exam',
            'subject' => 'Science',
        ]);

        $response = $this->actingAs($user)
            ->get(route('exams.index', ['search' => 'Mathematics']));

        $response->assertOk();
        $response->assertSee('Midterm Exam');
        $response->assertDontSee('Final Exam');
    }

    public function test_payments_index_can_filter_by_student_name_or_payment_date(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $student = Student::factory()->create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
        ]);

        Payment::create([
            'student_id' => $student->id,
            'school_class_id' => null,
            'amount' => 200,
            'total_fee' => 500,
            'balance' => 300,
            'status' => 'partial',
            'payment_method' => 'cash',
            'receipt_number' => 'RCP-2026-001',
            'payment_date' => '2026-09-10',
            'description' => 'School fee',
        ]);

        Payment::create([
            'student_id' => Student::factory()->create([
                'name' => 'Diana White',
                'email' => 'diana@example.com',
            ])->id,
            'school_class_id' => null,
            'amount' => 500,
            'total_fee' => 500,
            'balance' => 0,
            'status' => 'paid',
            'payment_method' => 'bank_transfer',
            'receipt_number' => 'RCP-2026-002',
            'payment_date' => '2026-09-15',
            'description' => 'School fee',
        ]);

        $response = $this->actingAs($user)
            ->get(route('payments.index', ['search' => '2026-09-10']));

        $response->assertOk();
        $response->assertSee('Charlie Brown');
        $response->assertDontSee('Diana White');
    }
}
