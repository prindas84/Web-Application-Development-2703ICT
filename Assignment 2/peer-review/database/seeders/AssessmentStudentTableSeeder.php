<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class AssessmentStudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all courses with students and assessments
        $courses = Course::with('students', 'assessments')->get();

        // Iterate over each course
        foreach ($courses as $course) {
            // Get the students enrolled in the course
            $students = $course->students;

            // Get the assessments associated with the course
            $assessments = $course->assessments;

            // Iterate over each assessment
            foreach ($assessments as $assessment) {
                // For each student, attach a record in the pivot table
                foreach ($students as $student) {
                    $assessment->students()->attach($student->id, [
                        'complete' => false, // Default complete to false
                    ]);
                }
            }
        }
    }
}
