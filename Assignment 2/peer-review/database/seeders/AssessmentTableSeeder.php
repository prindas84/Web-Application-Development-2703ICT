<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Assessment;

class AssessmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set the score options available to choose from array_rand.
        $maxScores = [60, 70, 80, 90, 100];
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 2; $j++) {
                Assessment::create([
                    'assessment_title' => 'PEER REVIEW ' . ($i + 1) . '.' . ($j + 1),
                    'assessment_instruction' => ($i + 1) . '.' . ($j + 1) . ' - Ne meliore scaevola eam. Mel ex quis veri insolens, ut modo elit eos. Te saperet minimum consequuntur sea, duo an oratio graecis. Id mel graece suscipit perfecto, nec reque dicam tincidunt no. Vix ad albucius sententiae dissentias, omnium viderer saperet ut eam. Legimus theophrastus est cu.',
                    'reviews_required' => rand(1, 5),
                    'max_score' => $maxScores[array_rand($maxScores)],
                    'type' => ($j % 2 == 0) ? 'student-select' : 'teacher-select',
                    'due_date' => Carbon::create(2024, 10, $i + $j + 6)->format('d-m-Y'),
                    'due_time' => '17:00:00',
                    'course_id' => ($i + 1),
                ]);
            }
        }
    }
}
