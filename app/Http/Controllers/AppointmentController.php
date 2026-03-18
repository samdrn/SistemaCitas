<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => 'El doctor ya tiene una cita en ese horario'
            ], 422);
        }

        $appointment = Appointment::create($request->all());

        return response()->json($appointment, 201);
    }
}