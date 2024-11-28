<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Redirect to the dashboard.
        return redirect()->route('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
            'reviewee_id' => 'nullable|exists:users,id',
            'assessment_id' => 'required|exists:assessments,id',
            'review' => [
                'required',
                'string',
                function($attribute, $value, $fail) {
                    if (str_word_count($value) < 5) {
                        $fail('The ' . $attribute . ' must be at least 5 words.');
                    }
                },
            ],
        ]);

        // Create the review
        Review::create([
            'reviewer_id' => $request->input('reviewer_id'),
            'reviewee_id' => $request->input('reviewee_id'),
            'review' => $request->input('review'),
            'assessment_id' => $request->input('assessment_id'),
        ]);

        // Check if the user has now completed the required number of reviews
        $assessmentId = $request->input('assessment_id');
        $reviewerId = $request->input('reviewer_id');

        // Get the assessment
        $assessment = Assessment::findOrFail($assessmentId);

        // Count the reviews given by the user for this assessment
        $reviewsGivenCount = Review::where('assessment_id', $assessmentId)
            ->where('reviewer_id', $reviewerId)
            ->count();

        if ($assessment->type == 'student-select') {
            // If the user has given the required number of reviews, mark the assessment as complete
            if ($reviewsGivenCount >= $assessment->reviews_required) {
                $assessment->students()->updateExistingPivot($reviewerId, ['complete' => true]);
            }
        } else {
            // Get the user's group and find the count
            $userGroup = $assessment->groups()->where('user_id', $reviewerId)->first();
            $groupMembersCount = $assessment->groups()->where('group_number', $userGroup->group_number)->count();
            // Calculate required reviews as the number of group members minus the current user
            $requiredReviewsGiven = $groupMembersCount - 1;
            // If the user has given the required number of reviews, mark the assessment as complete
            if ($reviewsGivenCount >= $requiredReviewsGiven) {
                $assessment->students()->updateExistingPivot($reviewerId, ['complete' => true]);
            }
        }


        return redirect()->back()->with('success', 'Review added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the specific review by ID
        $review = Review::findOrFail($id);

        // Return the view to show the review details
        return view('reviews.show', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input, specifically the feedback_score (between 0 and 5)
        $request->validate([
            'feedback_score' => 'required|integer|min:0|max:5',
        ]);

        // Find the review by ID
        $review = Review::findOrFail($id);

        // Update the feedback_score
        $review->feedback_score = $request->input('feedback_score');

        // Save the changes
        $review->save();

        // Redirect back with a success message (or return a response if it's an API)
        return redirect()->back()->with('success', 'Review feedback score updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the review
        $review = Review::findOrFail($id);

        // Delete the review
        $review->delete();

        // Get the assessment and reviewer
        $assessmentId = $review->assessment_id;
        $reviewerId = $review->reviewer_id;

        // Get the assessment
        $assessment = Assessment::findOrFail($assessmentId);

        // Count the remaining reviews given by the user for this assessment
        $remainingReviewsCount = Review::where('assessment_id', $assessmentId)
        ->where('reviewer_id', $reviewerId)
        ->count();

        // If the remaining reviews are less than required, mark the assessment as incomplete
        if ($remainingReviewsCount < $assessment->reviews_required) {
            $assessment->students()->updateExistingPivot($reviewerId, ['complete' => false]);
        }

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
