<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\User;

class CourseTeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manually assign 2 teachers per course
        $teachers = [
            ['course_id' => 1, 'user_id' => 1],
            ['course_id' => 1, 'user_id' => 2],
            ['course_id' => 2, 'user_id' => 3],
            ['course_id' => 2, 'user_id' => 4],
            ['course_id' => 3, 'user_id' => 5],
            ['course_id' => 3, 'user_id' => 6],
            ['course_id' => 4, 'user_id' => 7],
            ['course_id' => 4, 'user_id' => 8],
            ['course_id' => 5, 'user_id' => 9],
            ['course_id' => 5, 'user_id' => 10],
        ];
        
        foreach ($teachers as $teacher) {
            $course = Course::find($teacher['course_id']);
            $user = User::find($teacher['user_id']);
            
            if ($course && $user) {
                $course->teachers()->attach($user->id);
            }
        }
    }
}
