<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_be_created_from_unpaid_page_and_redirects_back_to_search(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('students.store'), [
            'name' => 'New Student',
            'age' => 12,
            'email' => 'new-student@example.com',
            'class_name' => 'Grade 7',
            'return_to' => 'unpaid',
            'search' => 'Grade 7',
            'payment_date' => '2026-09-10',
        ]);

        $response->assertRedirect(route('students.unpaid', [
            'search' => 'Grade 7',
            'payment_date' => '2026-09-10',
        ]));
        $this->assertDatabaseHas('students', [
            'name' => 'New Student',
            'email' => 'new-student@example.com',
        ]);
    }

    public function test_student_can_be_updated_from_unpaid_page_and_redirects_back_to_search(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create([
            'name' => 'Before Update',
            'class_name' => 'Grade 7',
        ]);

        $response = $this->actingAs($user)->put(route('students.update', $student), [
            'name' => 'After Update',
            'age' => $student->age,
            'email' => $student->email,
            'class_name' => 'Grade 8',
            'return_to' => 'unpaid',
            'search' => 'Grade 8',
            'payment_date' => '2026-09-10',
        ]);

        $response->assertRedirect(route('students.unpaid', [
            'search' => 'Grade 8',
            'payment_date' => '2026-09-10',
        ]));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'After Update',
            'class_name' => 'Grade 8',
        ]);
    }

    public function test_student_can_be_deleted_from_unpaid_page_and_redirects_back_to_search(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->delete(route('students.destroy', $student), [
            'return_to' => 'unpaid',
            'search' => 'Grade 7',
            'payment_date' => '2026-09-10',
        ]);

        $response->assertRedirect(route('students.unpaid', [
            'search' => 'Grade 7',
            'payment_date' => '2026-09-10',
        ]));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
