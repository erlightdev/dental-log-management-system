<?php

namespace App\Filament\Resources\DoctorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PatientsRelationManager extends RelationManager
{
    protected static string $relationship = 'patients';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),
                Forms\Components\DatePicker::make('dob')
                    ->label('Date of Birth'),
                Forms\Components\Select::make('blood_type')
                    ->options([
                        'A+' => 'A+', 'A-' => 'A-',
                        'B+' => 'B+', 'B-' => 'B-',
                        'AB+' => 'AB+', 'AB-' => 'AB-',
                        'O+' => 'O+', 'O-' => 'O-',
                    ]),
                Forms\Components\Textarea::make('medical_history_current_medications'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('dob')
                    ->date(),
                Tables\Columns\TextColumn::make('blood_type'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('blood_type')
                    ->options([
                        'A+' => 'A+', 'A-' => 'A-',
                        'B+' => 'B+', 'B-' => 'B-',
                        'AB+' => 'AB+', 'AB-' => 'AB-',
                        'O+' => 'O+', 'O-' => 'O-',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
