<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\BillResource\Pages;
use App\Filament\Staff\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Billing & Payments';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Bill Information')
                    ->schema([
                        Forms\Components\TextInput::make('bill_number')
                            ->label('Bill Number')
                            ->required()
                            ->default(fn () => \App\Models\Bill::generateBillNumber())
                            ->readOnly(),
                        Forms\Components\Select::make('appointment_id')
                            ->label('Appointment')
                            ->options(function () {
                                return \App\Models\Appointment::query()
                                    ->where('status', 'completed')
                                    ->with(['client', 'service'])
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(fn ($appointment) => [
                                        $appointment->id => "{$appointment->client->name} - {$appointment->service->service_name} ({$appointment->appointment_date->format('M d, Y')})"
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $appointment = \App\Models\Appointment::find($state);
                                    if ($appointment) {
                                        $set('client_id', $appointment->client_id);
                                        $set('created_by', auth()->id());
                                        $set('bill_date', now()->format('Y-m-d'));
                                        $set('due_date', now()->addDays(30)->format('Y-m-d'));
                                        $set('subtotal', $appointment->service->price ?? 0);
                                        $set('total_amount', $appointment->service->price ?? 0);
                                    }
                                }
                            }),
                        Forms\Components\Hidden::make('client_id'),
                        Forms\Components\Hidden::make('created_by'),
                        Forms\Components\Select::make('bill_type')
                            ->label('Bill Type')
                            ->options([
                                'service' => 'Service',
                                'consultation' => 'Consultation',
                                'treatment' => 'Treatment',
                                'follow_up' => 'Follow-up',
                                'emergency' => 'Emergency',
                            ])
                            ->required()
                            ->default('service'),
                        
                        Forms\Components\Select::make('payment_type')
                            ->label('Payment Type')
                            ->options(function (Forms\Get $get) {
                                $appointmentId = $get('appointment_id');
                                if (!$appointmentId) {
                                    return ['full' => 'Full Payment'];
                                }
                                
                                $appointment = \App\Models\Appointment::find($appointmentId);
                                if (!$appointment || !$appointment->service) {
                                    return ['full' => 'Full Payment'];
                                }
                                
                                $options = ['full' => 'Full Payment (Pay all at once)'];
                                
                                if ($appointment->service->allows_staggered_payment) {
                                    $options['staggered'] = 'Staggered Payment (Installments)';
                                }
                                
                                return $options;
                            })
                            ->required()
                            ->default('full')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state === 'full') {
                                    $set('total_installments', null);
                                    $set('down_payment', null);
                                    $set('installment_amount', null);
                                }
                            })
                            ->helperText(fn (Forms\Get $get) => 
                                $get('payment_type') === 'staggered' 
                                    ? 'Client will pay in installments with a down payment'
                                    : 'Client will pay the full amount at once'
                            ),
                        
                        Forms\Components\Select::make('total_installments')
                            ->label('Number of Installments')
                            ->options(function (Forms\Get $get) {
                                $appointmentId = $get('appointment_id');
                                if (!$appointmentId) return [];
                                
                                $appointment = \App\Models\Appointment::find($appointmentId);
                                if (!$appointment || !$appointment->service) return [];
                                
                                $service = $appointment->service;
                                $min = $service->min_installments ?? 2;
                                $max = $service->max_installments ?? 6;
                                
                                $options = [];
                                for ($i = $min; $i <= $max; $i++) {
                                    $options[$i] = "$i installments";
                                }
                                
                                return $options;
                            })
                            ->required(fn (Forms\Get $get) => $get('payment_type') === 'staggered')
                            ->visible(fn (Forms\Get $get) => $get('payment_type') === 'staggered')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if (!$state) return;
                                
                                $appointmentId = $get('appointment_id');
                                if (!$appointmentId) return;
                                
                                $appointment = \App\Models\Appointment::find($appointmentId);
                                if (!$appointment || !$appointment->service) return;
                                
                                $totalAmount = floatval($get('total_amount') ?? 0);
                                $downPaymentPercentage = floatval($appointment->service->down_payment_percentage ?? 30);
                                
                                // Calculate down payment
                                $downPayment = round($totalAmount * ($downPaymentPercentage / 100), 2);
                                $set('down_payment', $downPayment);
                                
                                // Calculate installment amount
                                $remaining = $totalAmount - $downPayment;
                                $installmentAmount = round($remaining / intval($state), 2);
                                $set('installment_amount', $installmentAmount);
                            })
                            ->helperText(function (Forms\Get $get) {
                                $appointmentId = $get('appointment_id');
                                if (!$appointmentId) return '';
                                
                                $appointment = \App\Models\Appointment::find($appointmentId);
                                if (!$appointment || !$appointment->service) return '';
                                
                                $min = $appointment->service->min_installments ?? 2;
                                $max = $appointment->service->max_installments ?? 6;
                                
                                return "Service allows $min to $max installments";
                            }),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Staggered Payment Summary')
                    ->schema([
                        Forms\Components\TextInput::make('down_payment')
                            ->label('Down Payment Amount')
                            ->prefix('₱')
                            ->numeric()
                            ->readOnly()
                            ->helperText('Initial payment required'),
                        Forms\Components\TextInput::make('installment_amount')
                            ->label('Per Installment Amount')
                            ->prefix('₱')
                            ->numeric()
                            ->readOnly()
                            ->helperText('Amount per regular payment'),
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get) => $get('payment_type') === 'staggered'),

                Forms\Components\Section::make('Bill Details')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Bill description...')
                            ->rows(3),
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $subtotal = floatval($state);
                                $taxAmount = floatval($get('tax_amount'));
                                $discountAmount = floatval($get('discount_amount'));
                                $total = $subtotal + $taxAmount - $discountAmount;
                                $set('total_amount', $total);
                            }),
                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $subtotal = floatval($get('subtotal'));
                                $taxAmount = floatval($state);
                                $discountAmount = floatval($get('discount_amount'));
                                $total = $subtotal + $taxAmount - $discountAmount;
                                $set('total_amount', $total);
                            }),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $subtotal = floatval($get('subtotal'));
                                $taxAmount = floatval($get('tax_amount'));
                                $discountAmount = floatval($state);
                                $total = $subtotal + $taxAmount - $discountAmount;
                                $set('total_amount', $total);
                            }),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->required()
                            ->readOnly(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('bill_date')
                            ->label('Bill Date')
                            ->required()
                            ->default(now()->format('Y-m-d'))
                            ->native(false),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->required()
                            ->default(now()->addDays(30)->format('Y-m-d'))
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3),
                        Forms\Components\Textarea::make('terms_conditions')
                            ->label('Terms & Conditions')
                            ->rows(3),
                        Forms\Components\Checkbox::make('is_recurring')
                            ->label('Recurring Bill'),
                        Forms\Components\Select::make('recurring_frequency')
                            ->label('Recurring Frequency')
                            ->options([
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'yearly' => 'Yearly',
                            ])
                            ->visible(fn (Forms\Get $get) => $get('is_recurring')),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['client', 'appointment.service', 'createdBy']))
            ->columns([
                Tables\Columns\TextColumn::make('bill_number')
                    ->label('Bill #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment.service.service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bill_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'consultation' => 'primary',
                        'treatment' => 'warning',
                        'follow_up' => 'success',
                        'emergency' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'full' => 'Full',
                        'staggered' => 'Staggered',
                        default => 'Full',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'full' => 'success',
                        'staggered' => 'warning',
                        default => 'gray',
                    })
                    ->description(function ($record) {
                        if ($record->payment_type === 'staggered' && $record->total_installments) {
                            $paid = $record->payments()->where('status', 'completed')->count();
                            return "$paid/{$record->total_installments} paid";
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_balance')
                    ->label('Balance')
                    ->money('PHP')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'partial' => 'info',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('bill_date')
                    ->label('Bill Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('bill_type')
                    ->label('Bill Type')
                    ->options([
                        'service' => 'Service',
                        'consultation' => 'Consultation',
                        'treatment' => 'Treatment',
                        'follow_up' => 'Follow-up',
                        'emergency' => 'Emergency',
                    ]),
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Bills')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now()->toDateString())
                        ->whereNotIn('status', ['paid', 'cancelled']))
                    ->toggle(),
                Tables\Filters\Filter::make('recurring')
                    ->label('Recurring Bills')
                    ->query(fn (Builder $query): Builder => $query->where('is_recurring', true))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('staff.bill.print', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('add_payment')
                    ->label('Add Payment')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->url(fn ($record) => route('filament.staff.resources.payments.create', [
                        'bill_id' => $record->id
                    ]))
                    ->visible(function ($record) {
                        // For full payments, show if there's remaining balance
                        if ($record->payment_type === 'full') {
                            return $record->remaining_balance > 0;
                        }
                        
                        // For staggered payments, show if there are still payments to be made
                        if ($record->payment_type === 'staggered') {
                            $completedPayments = $record->payments()->where('status', 'completed')->count();
                            return $completedPayments < $record->total_installments;
                        }
                        
                        return false;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
