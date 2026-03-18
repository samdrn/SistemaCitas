<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }
}