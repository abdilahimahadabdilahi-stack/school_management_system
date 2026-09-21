<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolParent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'occupation',
        'address',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'parent_id');
    }
}
