<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'class_number',
        'section',
        'class_label',
        'class_teacher',
        'capacity',
    ];

    // Ardayda ku jirta fasalkan
    public function students()
    {
        return $this->hasMany(Student::class, 'class_name', 'class_label');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'school_class_subject')->withTimestamps();
    }

    public function studentsForDisplay(): Builder
    {
        return Student::query()->where(function (Builder $query): void {
            $query->whereRaw('LOWER(class_name) = ?', [strtolower($this->class_label)])
                ->orWhere(function (Builder $query): void {
                    $query->where('class_name', (string) $this->class_number)
                        ->whereRaw('LOWER(section) = ?', [strtolower($this->section)]);
                });
        });
    }

    // Lacagaha fasalkan
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
