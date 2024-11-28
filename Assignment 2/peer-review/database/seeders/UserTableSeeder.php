<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert 10 faculty members
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'user_number' => 'f000000' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'first_name' => 'Faculty',
                'surname' => 'Member' . ($i + 1),
                'email' => 'faculty' . ($i + 1) . '@griffith.com.au',
                'role' => 'faculty',
                'password' => Hash::make('password'),
            ]);
        }

        // Insert 50 students
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'user_number' => 's000000' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'first_name' => 'Student',
                'surname' => 'Number' . ($i + 1),
                'email' => 'student' . ($i + 1) . '@griffith.com.au',
                'role' => 'student',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
