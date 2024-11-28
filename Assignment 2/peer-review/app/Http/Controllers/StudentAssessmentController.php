<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\User;

class StudentAssessmentController extends Controller
{
    /**
     * Show the student assessment.
     */
    public function show($assessment_id, $student_id)
    {
        // Fetch the assessment by its ID
        $assessment = Assessment::findOrFail($assessment_id);

        // Fetch the parent course
        $course = $assessment->course;

        // Check if the current user is a teacher for the course
        if (!auth()->user()->coursesAsTeacher->contains($course->id)) {
            return redirect()->route('course.show', $course->id)->withErrors('You do not have permission to view this assessment.');
        }

        // Fetch the student by their ID
        $student = User::findOrFail($student_id);

        // Fetch the record from the pivot table (assessment_student) for this student and assessment
        $studentAssessment = $student->assessments()->where('assessment_id', $assessment_id)->firstOrFail();

        // Fetch the reviews the student has given for this assessment
        $reviewsGiven = $assessment->reviews()->where('reviewer_id', $student_id)->get();

        // Fetch the reviews the student has received for this assessment
        $reviewsReceived = $assessment->reviews()->where('reviewee_id', $student_id)->get();

        // Return the student assessment view with the assessment, student, reviews, and other data
        return view('submissions.student-assessment', compact('assessment', 'student', 'studentAssessment', 'reviewsGiven', 'reviewsReceived'));
    }

    public function updateScore(Request $request, $assessment_id, $student_id)
    {
        // Fetch the assessment by its ID
        $assessment = Assessment::findOrFail($assessment_id);

        // Fetch the parent course
        $course = $assessment->course;

        // Check if the current user is a teacher for the course
        if (!auth()->user()->coursesAsTeacher->contains($course->id)) {
            return redirect()->route('course.show', $course->id)->withErrors('You do not have permission to view this assessment.');
        }

        // Validate the incoming score, ensuring it does not exceed the max score for the assessment
        $request->validate([
            'score' => 'required|integer|min:0|max:' . $assessment->max_score,
        ]);

        // Fetch the student and the pivot table record (assessment_student)
        $student = User::findOrFail($student_id);
        $studentAssessment = $student->assessments()->where('assessment_id', $assessment_id)->firstOrFail();

        // Update the score in the pivot table
        $studentAssessment->pivot->score = $request->input('score');
        $studentAssessment->pivot->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Score updated successfully.');
    }

}


