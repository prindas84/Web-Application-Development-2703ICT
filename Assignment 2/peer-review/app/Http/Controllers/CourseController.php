<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CourseController extends Controller
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
     * Store a newly created course in the database.
     */
    public function store(Request $request)
    {
        // Validate the input to ensure correct data is provided
        $request->validate([
            'course_code' => 'required|unique:courses,course_code|max:10',
            'course_name' => 'required|max:50',
            'course_description' => 'nullable',
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if the user is a faculty member
        $user = User::findOrFail($request->user_id);
        
        if ($user->role !== 'faculty') {
            // If the user is not a faculty member, redirect back with an error message
            return redirect()->back()->with('error', 'Only faculty members can add courses.');
        }

        // Create a new course with the provided data
        $course = Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'course_description' => $request->course_description,
        ]);

        // Attach the user to the course as a teacher
        $course->teachers()->attach($user->id);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Course added successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(string $id)
    {
        // Fetch the course by its ID and include related teachers and students
        $course = Course::with(['teachers', 'students'])->findOrFail($id);
    
        // Get all students who are not enrolled in the course
        $nonEnrolledStudents = User::where('role', 'student')
            ->whereNotIn('id', $course->students->pluck('id')->toArray())
            ->orderBy('surname', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();

        // Return the course details view with the course and non-enrolled students data
        return view('courses.show', compact('course', 'nonEnrolledStudents'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit($id)
    {
        // Find the course by its ID
        $course = Course::findOrFail($id);
    
        // Check if the current user is a faculty member and is part of the teaching staff for this course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the course show page if the user is not allowed to edit the course
            return redirect()->route('course.show', $course->id)->with('error', 'You do not have permission to edit this course.');
        }
    
        // Return the edit view with the course data
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in the database.
     */
    public function update(Request $request, $id)
    {
        // Validate the updated course data, allowing for the current course code
        $request->validate([
            'course_code' => 'required|unique:courses,course_code,'.$id.'|max:10',
            'course_name' => 'required|max:50',
            'course_description' => 'nullable',
        ]);

        // Find the course by its ID
        $course = Course::findOrFail($id);

        // Check if the current user is a faculty member and is part of the teaching staff for this course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the course show page if the user is not allowed to edit the course
            return redirect()->route('course.show', $course->id)->with('error', 'You do not have permission to edit this course.');
        }

        // Update the course details
        $course->update([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'course_description' => $request->course_description,
        ]);

        // Redirect back to the dashboard with a success message
        return redirect()->route('course.show', $course->id)->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from the database.
     */
    public function destroy($id)
    {
        // Find the course by its ID
        $course = Course::findOrFail($id);

        // Check if the current user is a faculty member and is part of the teaching staff for this course
        if (auth()->user()->role !== 'faculty' || !$course->teachers->contains(auth()->user()->id)) {
            // Redirect to the course show page if the user is not allowed to edit the course
            return redirect()->route('course.show', $course->id)->with('error', 'You do not have permission to delete this course.');
        }
    
        // Delete the course from the database
        $course->delete();
    
        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Course deleted successfully.');
    }

    /**
     * Add a teacher to the course.
     */
    public function addTeacher(Request $request, $courseId)
    {
        // Validate the user_id to ensure the user exists
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Find the course by its ID
        $course = Course::findOrFail($courseId);

        // Check if the user is already a teacher for the course
        if ($course->teachers->contains($request->input('user_id'))) {
            // Redirect back with an error if the user is already a teacher
            return redirect()->back()->withErrors('This user is already a teacher for this course.');
        }

        // Check if the user is a faculty member
        $user = User::findOrFail($request->input('user_id'));
        if ($user->role !== 'faculty') {
            // Only faculty members can be added as teachers
            return redirect()->back()->withErrors('Only faculty members can be added as teachers.');
        }

        // Attach the teacher to the course
        $course->teachers()->attach($user->id);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Teacher added successfully.');
    }

    /**
     * Remove a teacher from the course.
     */
    public function removeTeacher(Request $request, $courseId)
    {
        // Validate that either user_id or teacher_id is provided
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);
    
        // Determine which ID to use: teacher_id or user_id
        $teacherId = $request->input('teacher_id') ?? $request->input('user_id');
    
        // Find the course by its ID
        $course = Course::findOrFail($courseId);
    
        // Check if the teacher is assigned to the course
        if (!$course->teachers->contains($teacherId)) {
            // If the user is not a teacher, redirect back with an error message
            return redirect()->back()->withErrors('This user is not a teacher for this course.');
        }
    
        // Detach the teacher from the course
        $course->teachers()->detach($teacherId);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Teacher removed successfully.');
    }    

    /**
     * Enrol a student in the course.
     */
    public function enrollStudent(Request $request, $courseId)
    {
        // Validate that either student_id or user_id is provided
        $request->validate([
            'student_id' => 'nullable|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
        ]);
    
        // Use student_id if provided, otherwise fall back to user_id
        $studentId = $request->input('student_id') ?? $request->input('user_id') ?? auth()->id();
    
        // Find the course by its ID
        $course = Course::findOrFail($courseId);
    
        // Attach the student to the course if they aren't already enrolled
        if (!$course->students->contains($studentId)) {
            
            // Enroll the student in the course
            $course->students()->attach($studentId);

            // Get all assessments for the course
            $assessments = $course->assessments;

            foreach ($assessments as $assessment) {
                // Attach the student to each assessment with default 'incomplete' status
                $assessment->students()->attach($studentId, ['complete' => false,]);

                // If the assessment is teacher-select, add the student to a group
                if ($assessment->type === 'teacher-select') {
                    // Get the group size for the assessment
                    $groupSize = $assessment->group_size;

                    // Get all groups for the assessment from the assessment_groups table
                    $groups = $assessment->groups()->orderBy('group_number', 'desc')->get();

                    // If there are groups, then place the student in them
                    if (!$groups->isEmpty()) {
                        // Initialise a flag to track whether the student is added
                        $studentAdded = false;

                        // Loop through the groups from the last group to the first
                        foreach ($groups as $group) {
                            // Count the number of students in the current group
                            $groupMemberCount = $assessment->groups()->where('group_number', $group->group_number)->count();

                            // If the group is not full, or is at the minimum group size
                            if ($groupMemberCount <= $groupSize) {
                                $assessment->groups()->create([
                                    'user_id' => $studentId,
                                    'group_number' => $group->group_number,
                                ]);
                                $studentAdded = true;
                                break;
                            }
                        }

                        // If the student was not added to any group, add them to the first group
                        if (!$studentAdded) {
                            $firstGroup = $groups->first();
                            $groupNumber = $firstGroup ? $firstGroup->group_number : 1; // Default to group 1 if no groups exist

                            $assessment->groups()->create([
                                'user_id' => $studentId,
                                'group_number' => $groupNumber,
                            ]);
                        }
                    }
                }
            }
        }
        
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Student enrolled successfully.');
    }

    /**
     * Unenrol a student from the course.
     */
    public function unenrollStudent(Request $request, $courseId)
    {
        // Validate that either student_id or user_id is provided
        $request->validate([
            'student_id' => 'nullable|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Use student_id if provided, otherwise fall back to user_id
        $userId = $request->input('student_id') ?? $request->input('user_id') ?? auth()->id();

        // Find the course by its ID
        $course = Course::findOrFail($courseId);

        // Detach the student from the course if they are enrolled
        if ($course->students->contains($userId)) {
            // Remover the student in the course
            $course->students()->detach($userId);

            // Detach the student from all assessments associated with the course
            foreach ($course->assessments as $assessment) {

                // Remove the student from the assessments
                $assessment->students()->detach($userId);

                // If the assessment is teacher-select, remove the student from the group
                if ($assessment->type === 'teacher-select') {
                    // Remove the student from the group for the current assessment
                    $assessment->groups()->where('user_id', $userId)->delete();
                }
            }
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Student removed successfully.');
    }


    public function uploadCourseData(Request $request)
    {
        // Validate the input file
        $request->validate([
            'course_file' => 'required|file|mimes:txt',
        ]);

        // Load the file contents
        $fileContent = File::get($request->file('course_file')->getRealPath());

        // Split the content by line breaks
        $lines = explode(PHP_EOL, $fileContent);

        // Initialise arrays to store the parsed data
        $courseDetails = [];
        $teachers = [];
        $students = [];
        $assessments = [];

        // Parse the lines
        foreach ($lines as $index => $line) {
            $line = trim($line); // Trim any whitespace

            if (empty($line)) {
                continue; // Skip empty lines
            }

            switch ($index) {
                case 0:
                    // Line 1: Course details (Course Code || Course Name || Course Description)
                    $courseDetails = explode('||', $line);

                    // Check if the course code already exists in the database
                    $existingCourse = Course::where('course_code', $courseDetails[0])->first();
                    if ($existingCourse) {
                        return redirect()->back()->withErrors('The course code already exists in the database.');
                    }
                    break;

                case 1:
                    // Line 2: Faculty Numbers (teacher ids, separated by "||")
                    $teachers = explode('||', $line);
                    break;

                case 2:
                    // Line 3: Student Numbers (student ids, separated by "||")
                    $students = explode('||', $line);
                    break;

                default:
                    // Lines 4+: Assessments (Assessment Title || Instructions || Reviews Required || Max Score || Type || Group Size || Due Date || Due Time)
                    $assessmentDetails = explode('||', $line);
                    if (count($assessmentDetails) == 8) {
                        $assessments[] = [
                            'title' => $assessmentDetails[0],
                            'instruction' => $assessmentDetails[1],
                            'reviews_required' => $assessmentDetails[2],
                            'max_score' => $assessmentDetails[3],
                            'type' => $assessmentDetails[4],
                            'group_size' => $assessmentDetails[5],
                            'due_date' => $assessmentDetails[6],
                            'due_time' => $assessmentDetails[7],
                        ];
                    }
                    break;
            }
        }

        // Store course, teachers, students, and assessments in the database
        $this->saveCourseData($courseDetails, $teachers, $students, $assessments);

        return redirect()->back()->with('success', 'Course and assessments uploaded successfully.');
    }

    private function saveCourseData($courseDetails, $teachers, $students, $assessments)
    {
        // Create the course
        $course = Course::create([
            'course_code' => $courseDetails[0],
            'course_name' => $courseDetails[1],
            'course_description' => $courseDetails[2],
        ]);

        $courseId = $course->id;

        // Find teacher IDs using the user numbers
        $teacherIds = User::whereIn('user_number', $teachers)->pluck('id')->toArray();

        // Attach the teachers to the course
        $course->teachers()->attach($teacherIds);

        // Find existing student IDs using the user numbers
        $existingStudents = User::whereIn('user_number', $students)->pluck('user_number', 'id')->toArray();

        // Get the list of student numbers that are not found in the database
        $missingStudents = array_diff($students, array_values($existingStudents));

        // Attach the valid student IDs to the course
        $studentIds = array_keys($existingStudents);

        // Attach the students to the course
        $course->students()->attach($studentIds);

        // Create assessments for the course
        foreach ($assessments as $assessmentData) {
            // Parse due_date and due_time to ensure they are in the correct format
            $dueDate = Carbon::createFromFormat('d/m/Y', $assessmentData['due_date'])->format('Y-m-d');
            $dueTime = Carbon::createFromFormat('g:i A', $assessmentData['due_time'])->format('H:i');

            $assessment = $course->assessments()->create([
                'assessment_title' => $assessmentData['title'],
                'assessment_instruction' => $assessmentData['instruction'],
                'reviews_required' => $assessmentData['reviews_required'],
                'max_score' => $assessmentData['max_score'],
                'type' => $assessmentData['type'],
                'group_size' => $assessmentData['group_size'],
                'due_date' => $dueDate,
                'due_time' => $dueTime,
                'course_id' => $courseId,
            ]);

            // Attach students to each assessment with default 'incomplete' status
            foreach ($studentIds as $studentId) {
                $assessment->students()->attach($studentId, ['complete' => false]);
            }
        }
        // If there are missing students, add them to the waitlist
        if (!empty($missingStudents)) {
            foreach ($missingStudents as $userNumber) {
                UserCourses::create([
                    'user_number' => $userNumber,
                    'course_id' => $course->id,
                ]);
            }
        }
    }


}
