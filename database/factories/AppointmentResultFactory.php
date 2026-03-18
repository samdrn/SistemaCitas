<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class AppointmentResultFactory extends Factory
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
    'appointment_id' => \App\Models\Appointment::factory(),
    'diagnostic' => $this->faker->paragraph(),
    'prescription' => $this->faker->paragraph(),
];
    }
}
