<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassInSchool;

class ClassInSchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            [
                'name' => 'A'
            ],
            [
                'name' => 'B'
            ],
            [
                'name' => 'C'
            ],
            [
                'name' => 'D'
            ],
            [
                'name' => 'E'
            ],
            [
                'name' => 'F'
            ]
        ];
        foreach ($classes as $class) {
            ClassInSchool::create($class);
        }
    }
}
