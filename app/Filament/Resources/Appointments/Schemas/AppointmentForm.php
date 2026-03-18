<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('patient_id')
                    ->required()
                    ->numeric(),
                TextInput::make('doctor_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required(),
            ]);
    }
}
