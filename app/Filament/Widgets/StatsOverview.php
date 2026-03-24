<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pacientes Registrados', Patient::count())
                ->description('Total en el sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Citas Hoy', Appointment::whereDate('start_time', today())->count())
                ->description('Citas programadas para hoy')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
            Stat::make('Médicos Activos', Doctor::count())
                ->description('Especialistas registrados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Citas Esta Semana', Appointment::whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])->count())
                ->description('Del lunes al domingo')
                ->descriptionIcon('heroicon-m-presentation-chart-bar')
                ->color('primary'),
        ];
    }
}
