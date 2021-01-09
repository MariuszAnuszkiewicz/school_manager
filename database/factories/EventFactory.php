<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Test with Geography '. $this->faker->unique(true)->numberBetween(1, 10),
            'date' => Carbon::now()->format('Y-m-d'),
            'start' => '08:00',
            'end' => '09:00',
        ];
    }
}
