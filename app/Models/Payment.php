<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_class_id',
        'amount',
        'total_fee',
        'balance',
        'status',
        'payment_method',
        'receipt_number',
        'payment_date',
        'description',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Ardayga lacagta bixiyay
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Fasalka uu ka tirsan yahay
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
