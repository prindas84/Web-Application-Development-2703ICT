<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assessment_title',
        'assessment_instruction',
        'reviews_required',
        'max_score',
        'type',
        'group_size',
        'due_date',
        'due_time',
        'course_id',
    ];

    /**
     * Get the course that the assessment belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the students associated with the assessment.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'assessment_student')
                    ->withPivot('complete', 'score')
                    ->withTimestamps();
    }

    /**
     * Get the reviews for the assessment.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Define the relationship to the AssessmentGroup model
    public function groups()
    {
        return $this->hasMany(AssessmentGroup::class);
    }

    public static function boot()
    {
        parent::boot();

        // Automatically detach related students when deleting the assessment
        static::deleting(function ($assessment) {
            $assessment->students()->detach(); // Remove the relationship
        });
    }
}
