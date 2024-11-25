<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Filament\Resources\PatientResource\RelationManagers\TreatmentsRelationManager;
use App\Filament\Resources\PatientResource\RelationManagers\ServicesRelationManager;
use App\Filament\Resources\PatientResource\RelationManagers\AppointmentsRelationManager;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Patient Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('patient_before_image')
                            ->label('Before Image')
                            ->nullable()
                            ->image()
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('patient_after_image')
                            ->label('After Image')
                            ->nullable()
                            ->image()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('gender')
                            ->required()
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ])
                            ->columnSpanFull(),
                        
                        Forms\Components\DatePicker::make('dob')
                            ->nullable()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('blood_type')
                            ->nullable()
                            ->options([
                                'A+' => 'A+', 'A-' => 'A-',
                                'B+' => 'B+', 'B-' => 'B-',
                                'AB+' => 'AB+', 'AB-' => 'AB-',
                                'O+' => 'O+', 'O-' => 'O-'
                            ])
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('address')
                            ->nullable()
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('medical_history_current_medications')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('treatment_name')
                            ->nullable()
                            ->maxLength(100)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('treatment_price')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('service_name')
                            ->nullable()
                            ->maxLength(100)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('service_price')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('initial_deposit')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('total_appointment_amount_deposits')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('total_amount')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\DatePicker::make('registration_date')
                            ->required()
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('notes')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'primary',
                        'female' => 'pink',
                        'other' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('treatment_name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('treatment_price')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service_price')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('registration_date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other'
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TreatmentsRelationManager::class,
            ServicesRelationManager::class,
            AppointmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
