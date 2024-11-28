<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourses extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'user_number',
        'course_id',
    ];

    // Define the relationship with the Course model (if needed)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

