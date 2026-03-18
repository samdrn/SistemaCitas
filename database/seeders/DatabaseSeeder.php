<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\AppointmentResult;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear datos base
        $doctors = Doctor::factory()->count(20)->create();
        $patients = Patient::factory()->count(30)->create();

        // DÍAS EN ESPAÑOL
        $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        // Crear horarios
        foreach ($doctors as $doctor) {
            foreach ($days as $day) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '16:00:00',
                ]);
            }
        }

        // Crear citas
        foreach ($doctors as $doctor) {

            $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
                ->where('day', 'viernes')
                ->first();

            if (!$schedule) continue;

            $date = Carbon::parse('next friday');

            $current = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

            while ($current < $end) {

                $appointmentEnd = $current->copy()->addMinutes(30);

                if ($appointmentEnd > $end) break;

                $patient = $patients->random();

                $exists = Appointment::where('doctor_id', $doctor->id)
                    ->where('start_time', $current)
                    ->exists();

                if (!$exists) {
                    $appointment = Appointment::create([
                        'doctor_id' => $doctor->id,
                        'patient_id' => $patient->id,
                        'start_time' => $current,
                        'end_time' => $appointmentEnd,
                    ]);

                    AppointmentResult::factory()->create([
                        'patient_id' => $appointment->patient_id,
                        'appointment_id' => $appointment->id,
                    ]);
                }

                $current->addMinutes(30);
            }
        }
    }
}