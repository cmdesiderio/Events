<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

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

		$frequency = $this->faker->randomElement(['Once-Off', 'Weekly', 'Monthly']);

		$end_date = null;
		if ($frequency != 'Once-Off'){
			$end_date = $this->faker->dateTimeBetween('now', '+1 years');
			
			$n = rand(0,5);
			if ($n == 0){
				$end_date = null;
			}
		} 

        return [
            'event_name' => $this->faker->text(30),
            'frequency'  => $frequency,
            'start_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'end_date'   => $end_date,
            'duration'   => rand(60,100)
        ];
    }
}
