<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'review',
        'assessment_id',
        'feedback_score',
    ];

    /**
     * Get the assessment that the review belongs to.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the reviewer (the user who wrote the review).
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the reviewee (the user who was reviewed).
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
