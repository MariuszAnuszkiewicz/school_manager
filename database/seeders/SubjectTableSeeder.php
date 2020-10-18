<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            [
                'name' => 'Chemistry'
            ],
            [
                'name' => 'Biology'
            ],
            [
                'name' => 'Phisics'
            ],
            [
                'name' => 'Geography'
            ],
            [
                'name' => 'Mathematics'
            ],
            [
                'name' => 'English'
            ],
            [
                'name' => 'Literature'
            ],
            [
                'name' => 'History'
            ]
        ];
        foreach ($subjects as $subject) {
            Subject::factory(1)->create($subject);
        }
    }
}
