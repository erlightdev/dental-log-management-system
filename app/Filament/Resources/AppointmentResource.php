<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Appointment Information')
                    ->schema([
                        Forms\Components\Select::make('patient_id')
                            ->relationship('patient', 'id')
                            ->getSearchResultsUsing(fn (string $search) => 
                                \App\Models\Patient::whereHas('user', fn ($query) => 
                                    $query->where('name', 'like', "%{$search}%")
                                )->limit(50)->get()->mapWithKeys(fn ($patient) => 
                                    [$patient->id => $patient->user->name]
                                )
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => 
                                \App\Models\Patient::find($value)?->user?->name
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('doctor_id')
                            ->relationship('doctor', 'id')
                            ->getSearchResultsUsing(fn (string $search) => 
                                \App\Models\Doctor::whereHas('user', fn ($query) => 
                                    $query->where('name', 'like', "%{$search}%")
                                )->limit(50)->get()->mapWithKeys(fn ($doctor) => 
                                    [$doctor->id => $doctor->user->name]
                                )
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => 
                                \App\Models\Doctor::find($value)?->user?->name
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('staff_id')
                            ->relationship('staff', 'id')
                            ->getSearchResultsUsing(fn (string $search) => 
                                \App\Models\Staff::whereHas('user', fn ($query) => 
                                    $query->where('name', 'like', "%{$search}%")
                                )->limit(50)->get()->mapWithKeys(fn ($staff) => 
                                    [$staff->id => $staff->user->name]
                                )
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => 
                                \App\Models\Staff::find($value)?->user?->name
                            )
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        
                        Forms\Components\DatePicker::make('appointment_date')
                            ->required()
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('estimated_duration')
                            ->nullable()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'scheduled' => 'Scheduled',
                                'reschedule' => 'Rescheduled',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled'
                            ])
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('cancellation_reason')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('appointment_amount_deposits')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('payment_status')
                            ->nullable()
                            ->options([
                                'paid' => 'Paid',
                                'unpaid' => 'Unpaid'
                            ])
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('total_appointment_amount_deposits')
                            ->required()
                            ->prefix('$')
                            ->numeric()
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
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('staff.user.name')
                    ->label('Staff')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'reschedule' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('total_appointment_amount_deposits')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'reschedule' => 'Rescheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled'
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid'
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
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
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
