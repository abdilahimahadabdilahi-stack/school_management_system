<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Subject;
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
            'email' => null,
        ]);
    }

    public function test_student_forms_do_not_show_an_email_input(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();

        $this->actingAs($user)
            ->get(route('students.create'))
            ->assertDontSee('name="email"', false);

        $this->actingAs($user)
            ->get(route('students.edit', $student))
            ->assertDontSee('name="email"', false);
    }

    public function test_student_forms_show_optional_core_subject_checkboxes(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();

        $createResponse = $this->actingAs($user)->get(route('students.create'));
        $editResponse = $this->actingAs($user)->get(route('students.edit', $student));

        foreach (Subject::CORE_SUBJECTS as $subjectName) {
            $createResponse->assertSee($subjectName);
            $editResponse->assertSee($subjectName);
        }

        $createResponse->assertSee('name="subjects[]"', false);
        $editResponse->assertSee('name="subjects[]"', false);
    }

    public function test_student_can_be_created_with_multiple_optional_subjects(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('students.store'), [
            'name' => 'Subject Student',
            'age' => 10,
            'class_name' => 'KG 1',
            'subject_selection_present' => '1',
            'subjects' => ['Arabic', 'Mathematics'],
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', [
            'name' => 'Subject Student',
            'subject' => 'Arabic, Mathematics',
        ]);
    }

    public function test_student_subject_selection_can_be_cleared_on_update(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create([
            'subject' => 'Arabic, Mathematics',
        ]);

        $response = $this->actingAs($user)->put(route('students.update', $student), [
            'name' => $student->name,
            'age' => $student->age,
            'class_name' => $student->class_name,
            'section' => $student->section,
            'subject_selection_present' => '1',
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'subject' => null,
        ]);
    }

    public function test_student_subject_selection_rejects_unlisted_subjects(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('students.store'), [
            'name' => 'Invalid Subject Student',
            'age' => 10,
            'class_name' => 'KG 1',
            'subject_selection_present' => '1',
            'subjects' => ['Unlisted Subject'],
        ]);

        $response->assertSessionHasErrors('subjects.0');
        $this->assertDatabaseMissing('students', [
            'name' => 'Invalid Subject Student',
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
