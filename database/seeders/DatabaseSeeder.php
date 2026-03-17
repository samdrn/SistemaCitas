<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $doctors = Doctor::factory()->count(10)->create();


        $patients = Patient::factory()->count(50)->create();

        $doctors->each(function ($doctor) {
            DoctorSchedule::factory()->count(3)->create([
                'doctor_id' => $doctor->id,
            ]);
        });


        $appointments = Appointment::factory()->count(100)->make()->each(function ($appointment) use ($patients, $doctors) {
            $appointment->patient_id = $patients->random()->id;
            $appointment->doctor_id = $doctors->random()->id;
            $appointment->save();
        });


        $appointments->each(function ($appointment) use ($patients) {
            MedicalRecord::factory()->create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
            ]);
        });
    }
}

