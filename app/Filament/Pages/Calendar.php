<?php

namespace App\Filament\Pages;
use App\Filament\Widgets\CalendarWidget;

use Filament\Pages\Page;

class Calendar extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'asistente', 'medico']);
    }
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected string $view = 'filament.pages.calendar';


    protected static string | \UnitEnum | null $navigationGroup = 'Gestión Clínica';

    protected static ?string $title = 'Calendario';

    protected function getHeaderWidgets(): array
    {
        return[
            CalendarWidget::class,
        ];
    }
    
}
