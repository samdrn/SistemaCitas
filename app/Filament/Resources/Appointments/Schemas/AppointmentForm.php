<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->label('Paciente')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->required(),
                Select::make('doctor_id')
                    ->label('Médico')
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required(),
            ]);
    }
}
