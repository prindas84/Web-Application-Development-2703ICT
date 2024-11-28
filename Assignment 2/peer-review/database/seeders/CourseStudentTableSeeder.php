<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\User;

class CourseStudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [1, 2, 3, 4, 5];

        // Get the student IDs
        $students = User::where('role', 'student')->pluck('id');

        // Assign each student to 4 random courses
        foreach ($students as $student_id) {
            // Randomly select 4 unique courses for the student
            $assignedCourses = collect($courses)->random(4);
            foreach ($assignedCourses as $course_id) {
                $course = Course::find($course_id);
                $course->students()->attach($student_id);
            }
        }
    }
}
