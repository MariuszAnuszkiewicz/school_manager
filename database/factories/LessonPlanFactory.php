<?php

namespace Database\Factories;

use App\Models\LessonPlan;
use App\Models\ClassInSchool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class LessonPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LessonPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subjects = [
            'Chemistry',
            'Biology',
            'Geography',
            'Mathematics',
            'Phisics',
            'English',
        ];

        return [
            'class_in_school_id' => Arr::random([ClassInSchool::all()[rand(0, count(ClassInSchool::all()) - 1)]->id]),
            'monday' => $this->faker->randomElement($subjects),
            'tuesday' => $this->faker->randomElement($subjects),
            'wednesday' => $this->faker->randomElement($subjects),
            'thursday' => $this->faker->randomElement($subjects),
            'friday' => $this->faker->randomElement($subjects),
        ];
    }
}
