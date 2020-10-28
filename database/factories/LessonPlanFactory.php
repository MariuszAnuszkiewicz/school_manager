<?php

namespace Database\Factories;

use App\Models\LessonPlan;
use App\Models\Subject;
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
        return [
            'class_in_school_id' => 1,
            'monday' => Arr::random([Subject::all()[rand(0, count(Subject::all()) - 1)]->name]),
            'tuesday' => Arr::random([Subject::all()[rand(0, count(Subject::all()) - 1)]->name]),
            'wednesday' => Arr::random([Subject::all()[rand(0, count(Subject::all()) - 1)]->name]),
            'thursday' => Arr::random([Subject::all()[rand(0, count(Subject::all()) - 1)]->name]),
            'friday' => Arr::random([Subject::all()[rand(0, count(Subject::all()) - 1)]->name]),
        ];
    }
}
