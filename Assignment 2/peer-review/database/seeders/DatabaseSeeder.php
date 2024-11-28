<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            CourseTableSeeder::class,
            AssessmentTableSeeder::class,
            CourseTeacherTableSeeder::class,
            CourseStudentTableSeeder::class,
            AssessmentStudentTableSeeder::class,
            ReviewTableSeeder::class,
        ]);
    }
}
