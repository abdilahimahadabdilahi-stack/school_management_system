<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_classes_are_listed_as_separate_class_pages(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
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

    public function test_class_page_only_displays_students_from_that_class(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);
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
        $user = User::factory()->create(['role' => 'teacher']);
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
}
