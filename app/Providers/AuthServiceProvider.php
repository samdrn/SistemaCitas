<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\PatientPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;



class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [User::class => UserPolicy::class, 
    Appointment::class => AppointmentPolicy::class, 
    Patient::class => PatientPolicy::class];
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
