<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\Filament\CalendarWidget as CalendarFilament;

class CalendarWidget extends CalendarFilament
{

    protected static bool $hideCalendar = false;

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.calendar');
    }

    protected function getEvents(FetchInfo $info):Collection
    {
        $query = Appointment::query()
            ->whereDate('end_time', '>=', $info->start)
            ->whereDate('start_time', '<=', $info->end);

        if (auth()->user()->hasRole('medico')) {
            $doctorId = auth()->user()->doctor?->id;
            $query->where('doctor_id', $doctorId);
        }

        return $query->get()->map(function($appointment){
            return CalendarEvent::make()
                ->title('Cita - ' . $appointment->patient->name . ' ' . $appointment->patient->last_name)
                ->start($appointment->start_time)
                ->end($appointment->end_time);
        });
    }
}

    
