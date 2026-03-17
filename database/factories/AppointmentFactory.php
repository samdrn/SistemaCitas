<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
return [
    'patient_id' => \App\Models\Patient::factory(),
    'doctor_id' => \App\Models\Doctor::factory(),
    'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
    'end_time' => function (array $attributes) {
        return \Carbon\Carbon::parse($attributes['start_time'])
            ->addMinutes(rand(30, 120));
    },
];
    }
}
