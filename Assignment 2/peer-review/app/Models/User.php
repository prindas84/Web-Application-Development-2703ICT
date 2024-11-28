<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that can be mass-assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_number',
        'first_name',
        'surname',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden when serialising the model.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Specify the attributes that should be cast to different data types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // The password is hashed before storing in the database
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the courses where the user is enrolled as a student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coursesAsStudent()
    {
        // Return the courses the user is enrolled in as a student
        return $this->belongsToMany(Course::class, 'course_student');
    }

    /**
     * Get the courses where the user is assigned as a teacher.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coursesAsTeacher()
    {
        // Return the courses the user is assigned to as a teacher
        return $this->belongsToMany(Course::class, 'course_teacher');
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_student')
                    ->withPivot('complete', 'score')
                    ->withTimestamps();
    }


    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    // Define the relationship to assessment groups
    public function groups()
    {
        return $this->hasMany(AssessmentGroup::class);
    }
}
