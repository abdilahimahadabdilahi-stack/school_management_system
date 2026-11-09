<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KgEvaluation extends Model
{
    protected $fillable = [
        'student_id',
        'school_class_id',
        'report_period',
        'skill',
        'rating',
        'notes',
        'evaluated_on',
    ];

    protected function casts(): array
    {
        return [
            'evaluated_on' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
}
