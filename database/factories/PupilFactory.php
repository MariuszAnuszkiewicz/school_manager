<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pupil;
use App\Models\ClassInSchool;
use Illuminate\Database\Eloquent\Factories\Factory;

class PupilFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pupil::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(User::role('pupil')->get()->first()->id, User::role('pupil')->get()->last()->id),
            'class_in_school_id' => $this->faker->numberBetween(1, count(ClassInSchool::all())),
        ];
    }
}
