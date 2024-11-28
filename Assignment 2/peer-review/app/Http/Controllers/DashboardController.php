<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all courses from the database, ordered by course code in ascending order
        $courses = Course::orderBy('course_code', 'asc')->get();
        
        // Fetch courses where the logged-in user is a teacher, ordered by course code
        $teacherCourses = Auth::user()->coursesAsTeacher()->orderBy('course_code', 'asc')->get();
        
        // Fetch courses where the logged-in user is a student, ordered by course code
        $studentCourses = Auth::user()->coursesAsStudent()->orderBy('course_code', 'asc')->get();
        
        // Fetch the assessments assigned to the logged-in student, ordered by due date
        $studentAssessments = Auth::user()->assessments()
            ->orderBy('due_date', 'asc')
            ->orderBy('due_time', 'asc')
            ->orderBy('assessment_title', 'asc')
            ->get();
        
        // Return the dashboard view with the courses, teacherCourses, studentCourses, and studentAssessments data
        return view('./dashboard/dashboard', compact('courses', 'teacherCourses', 'studentCourses', 'studentAssessments'));
    }
}
