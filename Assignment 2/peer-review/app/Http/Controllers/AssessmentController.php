<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Redirect to the dashboard which displays the full-course-list
        return redirect()->route('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the assessment data from the request
        $request->validate([
            'assessment_title' => 'required|string|max:20',
            'assessment_instruction' => 'required',
            'reviews_required' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'type' => 'required|in:student-select,teacher-select',
            'due_date' => 'required|date',
            'due_time' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Find the course
        $course = Course::findOrFail($request->course_id);

        // Check if the current user is faculty and part of the teaching team for the course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect back with an error message if they are not allowed to add assessments
            return redirect()->back()->withErrors('Only faculty members who are teaching this course can add assessments.');
        }

        // Create the new assessment
        $assessment = Assessment::create([
            'assessment_title' => $request->assessment_title,
            'assessment_instruction' => $request->assessment_instruction,
            'reviews_required' => $request->reviews_required,
            'max_score' => $request->max_score,
            'type' => $request->type,
            'due_date' => $request->due_date,
            'due_time' => $request->due_time,
            'course_id' => $request->course_id,
        ]);

        // Get all students enrolled in the course
        $students = $course->students;

        // Attach each student to the assessment_student table with 'complete' as false
        foreach ($students as $student) {
            $assessment->students()->attach($student->id, ['complete' => false]);
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Assessment added successfully and students have been assigned.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        // Fetch the assessment by its ID
        $assessment = Assessment::with('groups.user')->findOrFail($id);

        // Get the associated course for the assessment
        $course = $assessment->course;

        // Check if the user is either a teacher or a student enrolled in the course
        if (!$course->teachers->contains(auth()->user()->id) && !$course->students->contains(auth()->user()->id)) {
            // Redirect to the course page with an error if the user is not authorized
            return redirect()->route('course.show', $course->id)->withErrors('You do not have access to this assessment.');
        }

        // Fetch reviews for the logged-in user for this assessment
        $user = auth()->user();
        $reviewsGiven = $assessment->reviews()->where('reviewer_id', $user->id)->get();
        $reviewsReceived = $assessment->reviews()->where('reviewee_id', $user->id)->get();

        // Get data for the review encouragement display
        $allReviewsGiven = Review::where('reviewer_id', $user->id)->get();
        $reviewScoresCounted = 0;
        $reviewScoreTotal = 0;
        $reviewAverage = 0.00;
        foreach ($allReviewsGiven as $review) {
            if ($review->feedback_score > 0) {
                $reviewScoresCounted++;
                $reviewScoreTotal += $review->feedback_score;
            }
        }

        if ($reviewScoresCounted > 0) {
            $reviewAverage = round($reviewScoreTotal / $reviewScoresCounted, 2);
        }

        // Calculate the number of reviews the user still needs to provide
        if ($assessment->type == 'student-select') {
            $reviewsRequired = $assessment->reviews_required;
        }

        else {
            // Check if there are any groups associated with the assessment
            $groups = $assessment->groups;
            // If there are no groups, handle the case (either return an error or create a group)
            if ($groups->isEmpty() || $user->role === 'faculty') {
                $reviewsRequired = -1;
            }
            else {
                $userGroup = $assessment->groups->where('user_id', auth()->user()->id)->first();
            
                // Count the number of members in the same group
                $groupMembersCount = $assessment->groups->where('group_number', $userGroup->group_number)->count();
    
                // Exclude the logged-in user
                $reviewsRequired = $groupMembersCount - 1;
            }
        }
        
        $reviewsGivenCount = $reviewsGiven->count();
        $remainingReviews = max(0, $reviewsRequired - $reviewsGivenCount);

        // Get the page parameter from the query string
        $currentPage = $request->query('page', 1); // Default to page 1 if not set

        // Optionally, store the current page in the session if you need to persist it
        session(['current_page' => $currentPage]);

        $recordsPerPage = 10;
        $pageOffset = ($currentPage - 1) * $recordsPerPage;

        // Fetch students enrolled in the course
        $students = $course->students;

        // Prepare student data for the view
        $studentList = $students->map(function ($student) use ($assessment) {
            $reviewsSubmitted = $assessment->reviews()->where('reviewer_id', $student->id)->count();
            $reviewsReceived = $assessment->reviews()->where('reviewee_id', $student->id)->count();
            $assessmentStatus = $student->assessments()->where('assessment_id', $assessment->id)->first();
            $score = $assessmentStatus->pivot->score ?? 'Not Graded';
            $isComplete = $assessmentStatus->pivot->complete ? 'Complete' : 'Incomplete';

            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->surname,
                'reviews_submitted' => $reviewsSubmitted,
                'reviews_received' => $reviewsReceived,
                'score' => $score,
                'is_complete' => $isComplete,
            ];
        });

        // Sort by completed first (completed students come first), then by reviews submitted, and finally by name
        $sortedStudentList = $studentList->sortBy([
            ['completed_status', 'desc'], // Sort by completion status (1 for complete first)
            ['score', 'asc'], // Sort by score (-1 = Not Marked)
            ['reviews_submitted', 'desc'], // Sort by reviews submitted in descending order
            ['name', 'asc'], // Sort by student name in ascending order
        ]);

        // Slice the student list to get only the current page's students
        $paginatedStudentList = $sortedStudentList->slice($pageOffset, $recordsPerPage)->values();

        // Calculate total pages for pagination (total students divided by records per page)
        $totalStudents = $students->count();
        $totalPages = ceil($totalStudents / $recordsPerPage);

        // Fetch the groups and their users (without affecting the student pagination)
        $groups = $assessment->groups->map(function ($group) use ($assessment) {
            $student = $group->user;
            
            // Prepare additional data like reviews, score, and status for each student in the group
            $reviewsSubmitted = $assessment->reviews()->where('reviewer_id', $student->id)->count();
            $reviewsReceived = $assessment->reviews()->where('reviewee_id', $student->id)->count();
            $assessmentStatus = $student->assessments()->where('assessment_id', $assessment->id)->first();
            $score = $assessmentStatus->pivot->score ?? 'Not Graded';
            $isComplete = $assessmentStatus->pivot->complete ? 'Complete' : 'Incomplete';

            return [
                'group_number' => $group->group_number,
                'student' => [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'surname' => $student->surname,
                    'reviews_submitted' => $reviewsSubmitted,
                    'reviews_received' => $reviewsReceived,
                    'score' => $score,
                    'is_complete' => $isComplete,
                ]
            ];
        })->groupBy('group_number');

        // Sort by group number (or any other criteria like completion status)
        $sortedGroupList = $groups->sortBy('group_number');

        // Slice the group list to get only the current page's groups
        $paginatedGroupList = $sortedGroupList->slice($pageOffset, $recordsPerPage)->values();

        // Calculate total pages for pagination (total groups divided by records per page)
        $totalGroups = $groups->count();
        $totalGroupPages = ceil($totalGroups / $recordsPerPage);

        // Determine if the assessment is editable (i.e., it has no reviews)
        $editable = $assessment->reviews()->count() === 0;

        // Calculate the score for the logged-in user from the pivot table, only if they are a student
        $userScore = 0;
        if ($user->role === 'student') {
            $userAssessment = $assessment->students()->where('user_id', $user->id)->first();
            $userScore = $userAssessment->pivot->score;
        }

        // Return the view with the data, including the list of students and pagination info
        return view('assessments.show', compact('assessment', 'course', 'reviewsRequired', 'reviewsGiven', 'reviewsReceived', 'remainingReviews', 'reviewAverage', 'userScore', 'paginatedStudentList', 'currentPage', 'totalPages', 'pageOffset', 'totalStudents', 'totalGroupPages', 'paginatedGroupList', 'editable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the assessment by its ID
        $assessment = Assessment::findOrFail($id);
    
        // Get the course the assessment belongs to
        $course = $assessment->course;
    
        // Check if the current user is a faculty member and a teacher of the course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the assessment's show page if the user is not a teacher of the course
            return redirect()->route('assessment.show', $assessment->id)->withErrors('You do not have access to this assessment.');
        }
    
        // Determine if the assessment is editable (i.e., it has no reviews)
        $editable = $assessment->reviews()->count() === 0;

        // Return the view to show the assessment details, passing the $editable variable
        return view('assessments.edit', compact('assessment', 'editable'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request (excluding course_id)
        $request->validate([
            'assessment_title' => 'required|string|max:20',
            'assessment_instruction' => 'required',
            'reviews_required' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'type' => 'required|in:student-select,teacher-select',
            'due_date' => 'required|date',
            'due_time' => 'required',
        ]);
    
        // Find the assessment by ID
        $assessment = Assessment::findOrFail($id);

        // Get the course the assessment belongs to
        $course = $assessment->course;
    
        // Check if the current user is a faculty member and a teacher of the course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the assessment's show page if the user is not a teacher of the course
            return redirect()->route('assessment.show', $assessment->id)->withErrors('You do not have access to this assessment.');
        }
    
        // Update the assessment with validated data
        $assessment->update($request->only([
            'assessment_title',
            'assessment_instruction',
            'reviews_required',
            'max_score',
            'type',
            'due_date',
            'due_time',
        ]));
    
        // Redirect back to the course page with a success message
        return redirect()->route('assessment.show', $assessment->id)->with('success', 'Assessment updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the assessment by its ID
        $assessment = Assessment::findOrFail($id);

        // Get the course the assessment belongs to
        $course = $assessment->course;
            
        // Check if the current user is a faculty member and a teacher of the course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the assessment's show page if the user is not a teacher of the course
            return redirect()->route('assessment.show', $assessment->id)->withErrors('You do not have access to this assessment.');
        }
    
        // Get the course ID from the assessment
        $courseId = $assessment->course_id;
    
        // Delete the assessment
        $assessment->delete();
    
        // Redirect back to the course page with a success message
        return redirect()->route('course.show', $courseId)->with('success', 'Assessment deleted successfully.');
    }
    

    public function assignGroups(Request $request, $assessment_id)
    {
        // Fetch the assessment and the related course with its students
        $assessment = Assessment::with('course.students')->findOrFail($assessment_id);
        $course = $assessment->course;

        // Fetch all students enrolled in the course. Shuffle for random order
        $students = $course->students->shuffle();
        $total_students = $students->count();

        // Calculate the maximum group size as floor(total_students / 2)
        $max_group_size = floor($total_students / 2);

        // Validate the group size input, setting min to 2 and max to total_students / 2
        $request->validate([
            'group_size' => 'required|integer|min:2|max:' . $max_group_size,
        ]);

        // Assign validated group size
        $group_size = $request->group_size;

        // Check if the current user is allowed to assign groups (faculty and part of the course)
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            return redirect()->back()->withErrors('Only faculty members teaching this course can assign groups.');
        }

        // Determine if the assessment is editable (i.e., it has no reviews)
        if (!$assessment->reviews()->count() === 0) {
            return redirect()->back()->withErrors('This assessment cannot be edited at this time as it has completed reviews.');
        }

        // Clear all existing groups for this assessment
        $assessment->groups()->delete();

        // Update the group size in the assessment record
        $assessment->group_size = $group_size;
        $assessment->save();

        // Calculate the number of groups needed
        $group_count = floor($total_students / $group_size);

        // Calculate the overflow of students
        $studentOverflow = $total_students % $group_count;

        // Assign students to groups
        $count = 0;
        for ($i = 1; $i <= $group_count; $i++) {
            for ($j = 0; $j < $group_size && $count < $total_students; $j++) {
                $assessment->groups()->create([
                    'user_id' => $students[$count]->id,
                    'group_number' => $i,
                ]);
                $count++;
            }

            // Add remaining students to the last groups. 1 to each group.
            if ($i >= ($group_count - ($studentOverflow -1))) {
                $assessment->groups()->create([
                    'user_id' => $students[$count]->id,
                    'group_number' => $i,
                ]);
                $count++;
            }
        }

        return redirect()->route('assessment.show', $assessment->id)->with('success', 'Groups have been assigned successfully.');
    }

}
