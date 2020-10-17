<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create()->each(function ($admin) {
            $admin->assignRole('admin');
        });
        User::factory(8)->create()->each(function ($pupil) {
            $pupil->assignRole('pupil');
        });
        User::factory(1)->create()->each(function ($teacher) {
            $teacher->assignRole('teacher');
        });
    }
}
