<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentResultController;


Route::post('/doctors', [DoctorController::class, 'store']);
Route::get('/doctors/{id}/available-slots', [DoctorController::class, 'availableSlots']);

Route::post('/patients', [PatientController::class, 'store']);

Route::post('/appointments', [AppointmentController::class, 'store']);

Route::post('/appointment-results', [AppointmentResultController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');