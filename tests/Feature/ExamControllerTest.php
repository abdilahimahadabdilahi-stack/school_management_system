<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_forms_offer_the_core_subjects(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $exam = Exam::factory()->create(['subject' => 'Older Subject']);

        $createResponse = $this->actingAs($user)->get(route('exams.create'));
        $editResponse = $this->actingAs($user)->get(route('exams.edit', $exam));

        foreach (Subject::CORE_SUBJECTS as $subjectName) {
            $createResponse->assertSee($subjectName);
            $editResponse->assertSee($subjectName);
        }

        $createResponse->assertSee('name="subject"', false);
        $createResponse->assertSee('<option value="', false);
        $createResponse->assertSee('All Subjects');
        $editResponse->assertSee('Older Subject');
        $editResponse->assertSee('All Subjects');
    }

    public function test_exam_can_be_created_with_a_core_subject(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('exams.store'), [
            'name' => 'Midterm Exam',
            'subject' => 'Mathematics',
            'exam_type' => 'midterm',
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', [
            'name' => 'Midterm Exam',
            'subject' => 'Mathematics',
        ]);
    }

    public function test_exam_can_be_created_with_all_subjects_label(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('exams.store'), [
            'name' => 'School-Wide Exam',
            'subject' => 'All Subjects',
            'exam_type' => 'midterm',
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', [
            'name' => 'School-Wide Exam',
            'subject' => 'All Subjects',
        ]);
    }

    public function test_exam_creation_rejects_subjects_outside_the_core_catalog(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->from(route('exams.create'))->post(route('exams.store'), [
            'name' => 'Midterm Exam',
            'subject' => 'Physics',
            'exam_type' => 'midterm',
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $response->assertRedirect(route('exams.create'));
        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseMissing('exams', ['name' => 'Midterm Exam']);
    }

    public function test_exam_subject_can_be_changed_to_another_core_subject(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $exam = Exam::factory()->create(['subject' => 'Arabic']);

        $response = $this->actingAs($user)->put(route('exams.update', $exam), [
            'name' => $exam->name,
            'subject' => 'Af-Somali',
            'exam_type' => $exam->exam_type,
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'total_marks' => $exam->total_marks,
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', [
            'id' => $exam->id,
            'subject' => 'Af-Somali',
        ]);
    }

    public function test_existing_exam_with_legacy_subject_can_still_be_updated(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $exam = Exam::factory()->create(['subject' => 'Older Subject']);

        $response = $this->actingAs($user)->put(route('exams.update', $exam), [
            'name' => $exam->name,
            'subject' => 'Older Subject',
            'exam_type' => $exam->exam_type,
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'total_marks' => $exam->total_marks,
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', [
            'id' => $exam->id,
            'subject' => 'Older Subject',
        ]);
    }
}
