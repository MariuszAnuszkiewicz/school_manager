<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $semesters = [
            [
                'name' => '1'
            ],
            [
                'name' => '2'
            ],
        ];
        foreach ($semesters as $semester){
            Semester::create($semester);
        }
    }
}
