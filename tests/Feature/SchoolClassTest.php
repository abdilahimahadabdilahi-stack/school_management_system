<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_classes_are_listed_as_separate_class_pages(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $firstClass = SchoolClass::create([
            'class_number' => 1,
            'section' => 'A',
            'class_label' => '1A',
            'capacity' => 40,
        ]);
        $secondClass = SchoolClass::create([
            'class_number' => 1,
            'section' => 'B',
            'class_label' => '1B',
            'capacity' => 40,
        ]);

        $response = $this->actingAs($user)->get(route('classes.index'));

        $response->assertOk()
            ->assertSee(route('classes.show', $firstClass))
            ->assertSee(route('classes.show', $secondClass));
    }

    public function test_a_new_section_can_be_added_to_an_existing_class_level(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('classes.store'), [
            'class_number' => 1,
            'section' => 'C',
            'capacity' => 40,
        ]);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('school_classes', [
            'class_number' => 1,
            'section' => 'C',
            'class_label' => '1C',
        ]);
    }

    public function test_class_subjects_can_be_assigned_optionally(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $subject = Subject::where('slug', 'mathematics')->firstOrFail();

        $response = $this->actingAs($user)->post(route('classes.store'), [
            'class_number' => 2,
            'section' => 'A',
            'subject_ids' => [$subject->id],
        ]);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('school_class_subject', [
            'school_class_id' => SchoolClass::where('class_label', '2A')->value('id'),
            'subject_id' => $subject->id,
        ]);
    }

    public function test_class_subject_assignments_can_be_cleared_from_the_edit_form(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $schoolClass = SchoolClass::create([
            'class_number' => 3,
            'section' => 'A',
            'class_label' => '3A',
            'capacity' => 40,
        ]);
        $subject = Subject::where('slug', 'mathematics')->firstOrFail();
        $schoolClass->subjects()->attach($subject);

        $this->actingAs($user)
            ->get(route('classes.edit', $schoolClass))
            ->assertOk()
            ->assertSee('Mathematics');

        $this->actingAs($user)->put(route('classes.update', $schoolClass), [
            'class_number' => 3,
            'section' => 'A',
            'capacity' => 40,
        ])->assertRedirect(route('classes.index'));

        $this->assertDatabaseMissing('school_class_subject', [
            'school_class_id' => $schoolClass->id,
            'subject_id' => $subject->id,
        ]);
    }

    public function test_class_page_only_displays_students_from_that_class(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $schoolClass = SchoolClass::create([
            'class_number' => 2,
            'section' => 'A',
            'class_label' => '2A',
            'capacity' => 40,
        ]);
        Student::factory()->create([
            'name' => 'Student In Class',
            'class_name' => '2A',
            'section' => 'A',
        ]);
        Student::factory()->create([
            'name' => 'Student In Other Class',
            'class_name' => '2B',
            'section' => 'B',
        ]);

        $response = $this->actingAs($user)->get(route('classes.show', $schoolClass));

        $response->assertOk()
            ->assertSee('Student In Class')
            ->assertDontSee('Student In Other Class');
    }

    public function test_class_directory_count_matches_the_students_on_class_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $schoolClass = SchoolClass::create([
            'class_number' => 3,
            'section' => 'A',
            'class_label' => '3A',
            'capacity' => 40,
        ]);
        Student::factory()->count(2)->create([
            'class_name' => '3A',
            'section' => 'A',
        ]);
        Student::factory()->create([
            'class_name' => '3',
            'section' => 'a',
        ]);

        $indexResponse = $this->actingAs($user)->get(route('classes.index'));
        $showResponse = $this->actingAs($user)->get(route('classes.show', $schoolClass));

        $indexResponse->assertViewHas('classes', function ($classes) use ($schoolClass): bool {
            return $classes->firstWhere('id', $schoolClass->id)->students_count === 3;
        });
        $showResponse->assertViewHas('schoolClass', function (SchoolClass $loadedClass): bool {
            return $loadedClass->students->count() === 3;
        });
    }

    public function test_updating_a_class_keeps_enrolled_students_class_details_in_sync(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $schoolClass = SchoolClass::create([
            'class_number' => 4,
            'section' => 'A',
            'class_label' => '4A',
            'capacity' => 40,
        ]);
        $student = Student::factory()->create([
            'school_class_id' => $schoolClass->id,
            'class_name' => $schoolClass->class_label,
            'section' => $schoolClass->section,
        ]);

        $this->actingAs($user)->put(route('classes.update', $schoolClass), [
            'class_type' => 'primary',
            'class_number' => 4,
            'section' => 'C',
            'capacity' => 40,
        ])->assertRedirect(route('classes.index'));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'class_name' => '4C',
            'section' => 'C',
        ]);
    }
}
