<?php

namespace App\Http\Controllers;

use App\Models\AppointmentResult;
use App\Http\Requests\StoreAppointmentResultRequest;

class AppointmentResultController extends Controller
{
    public function store(StoreAppointmentResultRequest $request)
    {
        $result = AppointmentResult::create($request->validated());

        return response()->json([
            'message' => 'Resultado guardado correctamente',
            'data' => $result
        ], 201);
    }
}