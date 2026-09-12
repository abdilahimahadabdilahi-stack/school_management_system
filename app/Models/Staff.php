<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Soo import-gareey
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory; // 2. Ku dar halkan

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
    ];
}