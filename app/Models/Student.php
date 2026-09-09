<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'email',
        'class_name',
        'subject',
    ];

    // Xiriirka uu Student la leeyahay Attendance (Ardaygu wuxuu leeyahay xaadirino badan)
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}