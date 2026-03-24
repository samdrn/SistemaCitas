<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages;
use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestión Clínica';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Medico')) {
            $doctorId = auth()->user()->doctor?->id;
            return $query->where('doctor_id', $doctorId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Cita')
                    ->schema([
                        Select::make('patient_id')
                            ->label('Paciente')
                            ->relationship('patient', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('doctor_id')
                            ->label('Médico Tratante')
                            ->relationship('doctor', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} {$record->last_name}")
                            ->searchable()
                            ->required()
                            ->default(fn () => auth()->user()->doctor?->id)
                            ->disabled(fn () => auth()->user()->hasRole('Medico'))
                            ->dehydrated(),
                        DateTimePicker::make('start_time')
                            ->label('Inicio de Cita')
                            ->required()
                            ->native(false),
                        DateTimePicker::make('end_time')
                            ->label('Fin de Cita')
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Horario')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->formatStateUsing(fn ($record) => "{$record->patient->name} {$record->patient->last_name}")
                    ->searchable(['name', 'last_name']),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->formatStateUsing(fn ($record) => "{$record->doctor->name} {$record->doctor->last_name}")
                    ->visible(fn () => !auth()->user()->hasRole('Medico')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->getStateUsing(fn ($record) => now()->gt($record->end_time) ? 'Finalizada' : 'Pendiente')
                    ->color(fn ($state) => $state === 'Finalizada' ? 'gray' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->label('Filtrar por Médico')
                    ->options(Doctor::all()->pluck('name', 'id'))
                    ->visible(fn () => auth()->user()->hasAnyRole(['Admin', 'Asistente'])),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->hidden(fn () => auth()->user()->hasRole('Medico')),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->hidden(fn () => auth()->user()->hasRole('Medico')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/edit/{record}'),
        ];
    }
}
