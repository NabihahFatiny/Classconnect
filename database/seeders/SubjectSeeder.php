<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::firstOrCreate(
            ['name' => 'English'],
            [
                'code' => 'ENG',
                'description' => 'English language and literature studies',
            ]
        );

        Subject::firstOrCreate(
            ['name' => 'Mathematics'],
            [
                'code' => 'MATH',
                'description' => 'Mathematics and problem-solving',
            ]
        );
    }
}
