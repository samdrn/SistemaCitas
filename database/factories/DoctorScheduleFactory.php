<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class DoctorScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
return [
    'doctor_id' => \App\Models\Doctor::factory(),
    'day' => $this->faker->randomElement([
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ]),
    'start_time' => $start = $this->faker->time('H:i:s'),
    'end_time' => \Carbon\Carbon::createFromFormat('H:i:s', $start)
        ->addHours(rand(4, 8))
        ->format('H:i:s'),
];
    }
}
