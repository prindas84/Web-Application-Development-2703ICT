<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentGroup extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'assessment_id',
        'user_id',
        'group_number'
    ];

    // Relationship to the Assessment model
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
