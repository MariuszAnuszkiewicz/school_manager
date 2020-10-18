<?php

namespace Database\Seeders;

use App\Models\LessonPlan;
use Illuminate\Database\Seeder;

class LessonPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hours = [
            [
                'hours' => '8:00 - 8:45'
            ],
            [
                'hours' => '8:55 - 9:40'
            ],
            [
                'hours' => '9:50 - 10:35'
            ],
            [
                'hours' => '10:45 - 11:30'
            ],
            [
                'hours' => '11:50 - 12:35'
            ],
            [
                'hours' => '12:45 - 13:30'
            ],
            [
                'hours' => '13:40 - 14:25'
            ],
            [
                'hours' => '14:35 - 15:20'
            ],
            [
                'hours' => '15:30 - 16:15'
            ]
        ];
        foreach ($hours as $hour) {
            LessonPlan::factory(1)->create($hour);
        }
    }
}
