<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\SchoolParent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentClassSynchronizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_updated_student_details_match_on_the_student_list_and_class_roster(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $oldClass = SchoolClass::create([
            'class_number' => 4,
            'section' => 'A',
            'class_label' => '4A',
            'capacity' => 40,
        ]);
        $newClass = SchoolClass::create([
            'class_number' => 4,
            'section' => 'B',
            'class_label' => '4B',
            'capacity' => 40,
        ]);
        $parent = SchoolParent::create([
            'name' => 'Updated Parent',
            'email' => 'updated-parent@example.com',
        ]);
        $student = Student::factory()->create([
            'name' => 'Before Update',
            'age' => 12,
            'email' => 'before@example.com',
            'class_name' => '4A',
            'section' => 'A',
            'parent_id' => $parent->id,
            'subject' => 'Old Subject',
        ]);

        $updateResponse = $this->actingAs($user)->put(route('students.update', $student), [
            'name' => 'After Update',
            'age' => 13,
            'email' => 'after@example.com',
            'class_name' => '4B',
            'section' => null,
            'parent_id' => $parent->id,
            'subject' => 'New Subject',
        ]);

        $updateResponse->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'After Update',
            'age' => 13,
            'email' => 'after@example.com',
            'class_name' => '4B',
            'section' => null,
            'subject' => 'New Subject',
        ]);

        $studentListResponse = $this->get(route('students.index'));
        $newClassResponse = $this->get(route('classes.show', $newClass));
        $oldClassResponse = $this->get(route('classes.show', $oldClass));

        $studentDetails = [
            '#'.$student->id,
            'After Update',
            '13',
            'after@example.com',
            '4B',
            'N/A',
            'Updated Parent',
            'New Subject',
        ];

        $studentListResponse->assertSeeInOrder($studentDetails);
        $newClassResponse->assertSeeInOrder($studentDetails);
        $oldClassResponse->assertDontSee('After Update');
    }
}
