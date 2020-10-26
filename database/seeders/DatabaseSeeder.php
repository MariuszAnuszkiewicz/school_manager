<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SemesterTableSeeder::class);
        $this->call(ClassInSchoolTableSeeder::class);
        $this->call(SubjectTableSeeder::class);
        $this->call(LessonPlanTableSeeder::class);
        $this->call(PupilTableSeeder::class);
        $this->call(TeacherTableSeeder::class);
        $this->call(CommentTableSeeder::class);
        $this->call(RatingTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(EventTeacherTableSeeder::class);
    }
}
