<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentResultRequest extends FormRequest
{
    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnostic' => 'required|string',
            'prescription' => 'required|string',
        ];
    }
}