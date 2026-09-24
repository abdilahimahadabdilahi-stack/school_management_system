<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'subject',
        'marks_obtained',
        'total_marks',
    ];

    protected function casts(): array
    {
        return [
            'marks_obtained' => 'float',
            'total_marks' => 'float',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subjectRecord(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
