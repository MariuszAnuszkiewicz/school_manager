<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'teacher_id' => Teacher::where('user_id', User::role('teacher')->first()->id)->first()->id,
            'comment' => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
        ];
    }
}
