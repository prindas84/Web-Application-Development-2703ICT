<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that can be mass-assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_code',
        'course_name',
        'course_description',
    ];

    /**
     * Retrieve the students enrolled in the course, ordered by surname and then first name.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        // Return students associated with the course, ordered by surname and first name
        return $this->belongsToMany(User::class, 'course_student')
                    ->orderBy('surname', 'asc')
                    ->orderBy('first_name', 'asc');
    }

    /**
     * Retrieve the teachers assigned to the course, ordered by surname and then first name.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers()
    {
        // Return teachers associated with the course, ordered by surname and first name
        return $this->belongsToMany(User::class, 'course_teacher')
                    ->orderBy('surname', 'asc')
                    ->orderBy('first_name', 'asc');
    }

    /**
     * Retrieve the assessments associated with the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assessments()
    {
        // Return all assessments related to the course
        return $this->hasMany(Assessment::class);
    }

    /**
     * Determine if the current user is a teacher of this course and if they are faculty.
     *
     * @return bool
     */
    public function isCurrentUserTeacher()
    {
        // Get the current logged-in user
        $currentUser = Auth::user();
        
        // Check if the user is a teacher in the course and return true if they are
        return $this->teachers->contains($currentUser->id);
    }

    /**
     * Determine if the current user is enrolled in the course as a student.
     *
     * @return bool
     */
    public function isCurrentUserStudent()
    {
        // Get the current logged-in user
        $currentUser = Auth::user();
        
        // Check if the user is a student in the course and return true if they are
        return $this->students->contains($currentUser->id);
    }
}
