<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorController extends Controller
{
    //Crear doctor
    public function store(Request $request)
    {
        $doctor = Doctor::create($request->all());

        return response()->json([
            'message' => 'Doctor creado correctamente',
            'data' => $doctor
        ], 201);
    }

    //Obtener horarios disponibles del doctor
    public function availableSlots($id)
    {
        $date = request('date');

        if (!$date) {
            return response()->json([
                'error' => 'Debes enviar la fecha (?date=YYYY-MM-DD)'
            ], 400);
        }

        // Día en español
        Carbon::setLocale('es');
        $day = strtolower(Carbon::parse($date)->isoFormat('dddd'));

        // Buscar horario del doctor
        $schedule = DoctorSchedule::where('doctor_id', $id)
            ->where('day', $day)
            ->first();

        if (!$schedule) {
            return response()->json([
                'slots' => [],
                'message' => 'El doctor no trabaja este día'
            ]);
        }

        $start = Carbon::parse($date . ' ' . $schedule->start_time);
        $end   = Carbon::parse($date . ' ' . $schedule->end_time);

        $slots = [];

        // Generar bloques de 30 minutos
        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd   = $start->copy()->addMinutes(30);

            // Verificar si ya existe cita en ese horario
            $conflict = Appointment::where('doctor_id', $id)
                ->where('start_time', $slotStart)
                ->exists();

            if (!$conflict) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end'   => $slotEnd->format('H:i'),
                ];
            }

            $start->addMinutes(30);
        }

        return response()->json([
            'date' => $date,
            'slots' => $slots
        ]);
    }
}