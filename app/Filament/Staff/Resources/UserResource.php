<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\UserResource\Pages;
use App\Filament\Staff\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dompdf\Dompdf;
use Dompdf\Options;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Clients';
    
    protected static ?string $modelLabel = 'Client';
    
    protected static ?string $pluralModelLabel = 'Clients';
    
    protected static ?string $navigationGroup = 'Client Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn('edit'),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->disabled(),
                Forms\Components\Hidden::make('role')
                    ->default('Client'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('appointments_count')
                    ->label('Appointments')
                    ->counts('appointments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feedbacks_count')
                    ->label('Feedbacks')
                    ->counts('feedbacks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('email_verified')
                    ->label('Email Verified')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('generateReport')
                    ->label('Generate Report')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function (User $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo static::generateClientReportPDF($record);
                        }, "client-report-{$record->name}-" . now()->format('Y-m-d') . ".pdf", [
                            'Content-Type' => 'application/pdf',
                        ]);
                    }),
                Tables\Actions\Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Removed delete for safety
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AppointmentsRelationManager::class,
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'Client');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function generateClientReportPDF(User $client): string
    {
        // Load client with all related data
        $client->load([
            'appointments.service',
            'appointments.staff',
            'feedbacks.appointment.service'
        ]);

        // Get appointments statistics
        $totalAppointments = $client->appointments->count();
        $completedAppointments = $client->appointments->where('status', 'COMPLETED')->count();
        $pendingAppointments = $client->appointments->where('status', 'PENDING')->count();
        $cancelledAppointments = $client->appointments->whereIn('status', ['CANCELLED', 'DECLINED'])->count();

        // Get services availed
        $servicesAvailed = $client->appointments
            ->where('status', 'COMPLETED')
            ->groupBy('service.service_name')
            ->map(function ($appointments) {
                return [
                    'service' => $appointments->first()->service->service_name,
                    'count' => $appointments->count(),
                    'total_paid' => $appointments->sum('service.price')
                ];
            })->values();

        // Calculate total revenue from this client
        $totalRevenue = $client->appointments
            ->where('status', 'COMPLETED')
            ->sum('service.price');

        // Generate HTML content
        $html = view('reports.client-report', compact(
            'client',
            'totalAppointments',
            'completedAppointments', 
            'pendingAppointments',
            'cancelledAppointments',
            'servicesAvailed',
            'totalRevenue'
        ))->render();

        // Create PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
