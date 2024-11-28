<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Course::create([
                'course_code' => ($i + 1) . '00' . ($i + 1) . 'ICT',
                'course_name' => 'Griffith Demonstration Course - ' . ($i + 1) . '00' . ($i + 1) . 'ICT',
                'course_description' => ($i + 1) . ' - Lorem ipsum dolor sit amet, his no altera labore eripuit, usu no vero discere accommodare. Cu pri velit denique phaedrum, te per clita audiam admodum, graece facilisi ut sit. Mei inani vituperata ea, ius cu nullam possit. Sit at zril mucius veritus. In quo nominavi placerat petentium, debet clita disputando cu eos, dolor vocent adipisci vel ex.',
            ]);
        }
    }
}
