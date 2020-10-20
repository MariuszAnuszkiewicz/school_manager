<?php

namespace Database\Seeders;

use App\Models\Pupil;
use Illuminate\Database\Seeder;

class PupilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pupil::factory(8)->create();
    }
}
