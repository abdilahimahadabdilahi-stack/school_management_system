<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'exam_type',
        'exam_date',
        'start_time',
        'total_marks',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}
