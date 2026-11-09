<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $class_name
 * @property string|null $section
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'school_class_id',
        'name',
        'age',
        'email',
        'class_name',
        'section',
        'subject',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SchoolParent::class, 'parent_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function kgEvaluations(): HasMany
    {
        return $this->hasMany(KgEvaluation::class);
    }

    public function scopeWithExamPerformance(Builder $query): Builder
    {
        $examPerformance = ExamResult::query()
            ->selectRaw('student_id, SUM(marks_obtained) AS total_obtained, SUM(total_marks) AS total_possible_marks')
            ->where('total_marks', '>', 0)
            ->groupBy('student_id');

        return $query
            ->joinSub($examPerformance, 'exam_performance', 'exam_performance.student_id', '=', 'students.id')
            ->addSelect([
                'exam_performance.total_obtained',
                'exam_performance.total_possible_marks',
            ]);
    }

    /**
     * Calculate the student's score across every subject in a given exam,
     * treating unrecorded subjects as zero so the total reflects the full exam.
     */
    public function examResultsTotals(Exam $exam, ?Collection $existingResults = null, ?Collection $subjects = null): array
    {
        $results = collect($existingResults ?? $this->examResults()
            ->where('exam_id', $exam->id)
            ->with('subjectRecord')
            ->get())
            ->where('student_id', $this->id)
            ->values();

        $subjects = $subjects ?? Subject::query()->orderBy('sort_order')->get();

        $subjectRows = $subjects->map(function (Subject $subject) use ($results, $exam): array {
            $result = $results->first(
                fn (ExamResult $examResult): bool => $examResult->subject_id === $subject->id
                    || ($examResult->subject_id === null && $examResult->subject === $subject->name),
            );

            $totalMarks = (float) ($result?->total_marks ?? $exam->total_marks);
            $marksObtained = (float) ($result?->marks_obtained ?? 0.0);

            return [
                'record' => $result,
                'subject' => $subject,
                'marks_obtained' => $marksObtained,
                'total_marks' => $totalMarks,
                'percentage' => $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0.0,
            ];
        })->values();

        $totalObtained = (float) $subjectRows->sum('marks_obtained');
        $totalMarks = (float) $subjectRows->sum('total_marks');
        $percentage = $totalMarks > 0 ? round(($totalObtained / $totalMarks) * 100, 2) : 0.0;

        return [
            'results' => $subjectRows,
            'total_obtained' => $totalObtained,
            'total_marks' => $totalMarks,
            'percentage' => $percentage,
        ];
    }
}
