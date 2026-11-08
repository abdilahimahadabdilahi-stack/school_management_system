<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 */
class Subject extends Model
{
    use HasFactory;

    public const CORE_SUBJECTS = [
        'tarbia-islamic-studies' => 'Tarbia (Islamic Studies)',
        'arabic' => 'Arabic',
        'af-somali' => 'Af-Somali',
        'english' => 'English',
        'science' => 'Science',
        'mathematics' => 'Mathematics',
        'social-studies' => 'Social Studies',
    ];

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'school_class_subject')->withTimestamps();
    }

    public function scopeCore(Builder $query): Builder
    {
        return $query->whereIn('slug', array_keys(self::CORE_SUBJECTS));
    }
}
