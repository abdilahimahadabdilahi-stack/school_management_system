<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $class_number
 * @property string $section
 * @property string $class_label
 * @property int $capacity
 * @property int $students_count
 */
class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'class_type',
        'class_number',
        'section',
        'class_label',
        'class_teacher',
        'capacity',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school_class_id');
    }

    public function isKindergarten(): bool
    {
        return $this->class_type === 'kindergarten';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw("CASE WHEN class_type = 'primary' THEN 0 ELSE 1 END")
            ->orderBy('class_number')
            ->orderBy('section');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'school_class_subject')->withTimestamps();
    }

    public function studentsForDisplay(): Builder
    {
        return Student::query()->where(function (Builder $query): void {
            $query->where('school_class_id', $this->id)
                ->orWhere(function (Builder $query): void {
                    $query->whereNull('school_class_id')
                        ->where(function (Builder $query): void {
                            $query->whereRaw('LOWER(class_name) = ?', [strtolower($this->class_label)])
                                ->orWhere(function (Builder $query): void {
                                    $className = $this->isKindergarten()
                                        ? 'KG '.$this->class_number
                                        : (string) $this->class_number;
                                    $query->where('class_name', $className)
                                        ->whereRaw('LOWER(section) = ?', [strtolower($this->section)]);
                                });
                        });
                });
        });
    }

    public function kgEvaluations(): HasMany
    {
        return $this->hasMany(KgEvaluation::class, 'school_class_id');
    }

    // Lacagaha fasalkan
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
