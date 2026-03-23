<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestión Clínica';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Datos Personales')
                    ->description('Información básica del paciente.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombres')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Apellidos')
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email(),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Información del Paciente')
                                ->schema([
                                    Infolists\Components\TextEntry::make('name')->label('Nombres')->weight('bold'),
                                    Infolists\Components\TextEntry::make('last_name')->label('Apellidos')->weight('bold'),
                                    Infolists\Components\TextEntry::make('birth_date')->label('Fecha Nacimiento')->date(),
                                    Infolists\Components\TextEntry::make('phone')->label('Teléfono')->copyable(),
                                    Infolists\Components\TextEntry::make('email')->label('Email')->icon('heroicon-m-envelope'),
                                ])->columns(2),
                        ]),
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Expediente Clínico (Historial)')
                                ->description('Últimos diagnósticos y recetas.')
                                ->schema([
                                    // Mostramos una lista de resultados de citas
                                    Infolists\Components\RepeatableEntry::make('medicalRecords')
                                        ->label('Resultados de Consultas')
                                        ->schema([
                                            Infolists\Components\TextEntry::make('created_at')
                                                ->label('Fecha')
                                                ->dateTime()
                                                ->color('info'),
                                            Infolists\Components\TextEntry::make('diagnostic')
                                                ->label('Diagnóstico')
                                                ->markdown(),
                                            Infolists\Components\TextEntry::make('prescription')
                                                ->label('Receta Medica'),
                                        ])
                                        ->limit(5)
                                        ->emptyStateHeading('No hay historial registrado aún.'),
                                ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Edad')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                // REGLA: El Asistente puede registrar pero NO editar expediente (aquí editamos al paciente)
                // Se oculta la edición para Asistentes si queremos restringirlos totalmente:
                \Filament\Actions\EditAction::make()
                    ->hidden(fn () => auth()->user()->hasRole('Asistente')),
                // REGLA: El Medico no puede eliminar pacientes
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
