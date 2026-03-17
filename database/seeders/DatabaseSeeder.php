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

        $doctors = Doctor::factory()->count(5)->create();
        $patients = Patient::factory()->count(30)->create();


        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $doctors->each(function ($doctor) use ($days) {
            foreach ($days as $day) {
                DoctorSchedule::factory()->create([
                    'doctor_id' => $doctor->id,
                    'day' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '16:00:00',
                ]);
            }
        });


        foreach ($doctors as $doctor) {
            $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get();

            foreach ($schedules as $schedule) {


                $date = Carbon::now()->next($schedule->day);

                $start = Carbon::parse($schedule->start_time);
                $end = Carbon::parse($schedule->end_time);

                $current = $date->copy()->setTimeFrom($start);
                $patient;

                for ($i = 0; $i < 5; $i++) {
                    $patient = $patients->random();

                    $conflict = collect($patientBookings[$patient->id] ?? [])->contains(
                        fn ($b) => $current < $b['end'] && $appointmentEnd > $b['start']
                    );

                    if (!$conflict) {
                        break;
                    }
}

                while ($current->lt($date->copy()->setTimeFrom($end))) {

                    $duration = rand(30, 60);
                    $appointmentEnd = $current->copy()->addMinutes($duration);

                    if ($appointmentEnd->gt($date->copy()->setTimeFrom($end))) {
                        break;
                    }

                    $appointment = Appointment::create([
                        'doctor_id' => $doctor->id,
                        'patient_id' => $patient->id,
                        'start_time' => $current->copy(),
                        'end_time' => $appointmentEnd->copy(),
                    ]);


                    AppointmentResult::factory()->create([
                        'patient_id' => $appointment->patient_id,
                        'appointment_id' => $appointment->id,
                    ]);

                    $current = $appointmentEnd;
                }
            }
        }
    }
}