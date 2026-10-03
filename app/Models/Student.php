<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
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

    /**
     * Calculate the student's score across every subject in a given exam,
     * treating unrecorded subjects as zero so the total reflects the full exam.
     */
    public function examResultsTotals(Exam $exam, ?Collection $existingResults = null, ?Collection $subjects = null): array
    {
        $results = $existingResults ?? $this->examResults()
            ->where('exam_id', $exam->id)
            ->with('subjectRecord')
            ->get();

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
