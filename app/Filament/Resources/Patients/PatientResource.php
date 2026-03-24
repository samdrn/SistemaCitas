<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages;
use App\Models\Patient;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;
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
                Section::make('Datos Personales')
                    ->description('Información básica del paciente.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombres')
                            ->required(),
                        TextInput::make('last_name')
                            ->label('Apellidos')
                            ->required(),
                        DatePicker::make('birth_date')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email(),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Group::make([
                            Section::make('Información del Paciente')
                                ->schema([
                                    TextEntry::make('name')->label('Nombres')->weight('bold'),
                                    TextEntry::make('last_name')->label('Apellidos')->weight('bold'),
                                    TextEntry::make('birth_date')->label('Fecha Nacimiento')->date(),
                                    TextEntry::make('phone')->label('Teléfono')->copyable(),
                                    TextEntry::make('email')->label('Email')->icon('heroicon-m-envelope'),
                                ])->columns(2),
                        ]),
                        Group::make([
                            Section::make('Expediente Clínico (Historial)')
                                ->description('Últimos diagnósticos y recetas.')
                                ->schema([
                                    RepeatableEntry::make('medicalRecords')
                                        ->label('Resultados de Consultas')
                                        ->schema([
                                            TextEntry::make('created_at')
                                                ->label('Fecha')
                                                ->dateTime()
                                                ->color('info'),
                                            TextEntry::make('diagnostic')
                                                ->label('Diagnóstico')
                                                ->markdown(),
                                            TextEntry::make('prescription')
                                                ->label('Receta Medica'),
                                        ]),
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
                    ->getStateUsing(fn ($record) => \Carbon\Carbon::parse($record->birth_date)->age . ' años')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make()
                    ->hidden(fn () => auth()->user()->hasRole('Asistente')),
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
