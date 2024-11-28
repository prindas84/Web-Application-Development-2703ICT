<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Str;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the specific user by their user number
        $targetUser = User::where('user_number', 's00000001')->first();

        $courses = Course::whereHas('students', function($query) use ($targetUser) {
            $query->where('users.id', $targetUser->id);
        })->with(['students', 'assessments'])->get();

        // Iterate over each course
        foreach ($courses as $course) {
            // Get the students enrolled in the course
            $students = $course->students;

            // Get the assessments associated with the course
            $assessments = $course->assessments;

            foreach ($assessments as $assessment) {
                // Check if assessment type is 'student-select'
                if ($assessment->type === 'student-select') {
                    // Some assessment is complete, some not. Can't use modulo because every even is teacher-select
                    if (rand(0, 1)) {
                        $requiredReviewsGiven = $assessment->reviews_required - 1;
                    }
                    else {
                        $requiredReviewsGiven = $assessment->reviews_required;
                        $assessment->students()->updateExistingPivot($targetUser->id, ['complete' => true]);
                    }
                    
                    // Set the number of reviews to receive
                    $requiredReviewsReceived = $assessment->reviews_required;

                    // Get students who will participate in the review process
                    $reviewingStudents = $students->where('id', '!=', $targetUser->id);

                    // Select random students to review the target user
                    $reviewers = $reviewingStudents->random($requiredReviewsReceived);

                    // Create reviews for the target user
                    foreach ($reviewers as $reviewer) {
                        Review::create([
                            'reviewer_id' => $reviewer->id,
                            'reviewee_id' => $targetUser->id,
                            'assessment_id' => $assessment->id,
                            'review' => Str::random(50),
                            'feedback_score' => 0,
                        ]);
                    }

                    // Ensure the target user reviews other students
                    $reviewedStudents = $students->where('id', '!=', $targetUser->id)->random($requiredReviewsGiven);

                    foreach ($reviewedStudents as $reviewedStudent) {
                        Review::create([
                            'reviewer_id' => $targetUser->id,
                            'reviewee_id' => $reviewedStudent->id,
                            'assessment_id' => $assessment->id,
                            'review' => Str::random(50),
                            'feedback_score' => rand(1, 5),
                        ]);
                    }
                }
            }
        }
    }
}
