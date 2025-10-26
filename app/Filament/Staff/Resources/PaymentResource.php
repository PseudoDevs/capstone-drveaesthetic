<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\PaymentResource\Pages;
use App\Filament\Staff\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Billing & Payments';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('payment_number')
                            ->label('Payment Number')
                            ->required()
                            ->default(fn () => \App\Models\Payment::generatePaymentNumber())
                            ->readOnly(),
                        Forms\Components\Select::make('bill_id')
                            ->label('Bill')
                            ->options(function () {
                                return \App\Models\Bill::query()
                                    ->where('remaining_balance', '>', 0)
                                    ->with(['client', 'appointment.service'])
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(fn ($bill) => [
                                        $bill->id => "{$bill->bill_number} - {$bill->client->name} - ₱{$bill->remaining_balance} balance"
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->default(function () {
                                // Pre-select bill if bill_id is in URL
                                $billId = request()->get('bill_id');
                                return $billId ? (int) $billId : null;
                            })
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $bill = \App\Models\Bill::find($state);
                                    if ($bill) {
                                        $set('client_id', $bill->client_id);
                                        $set('appointment_id', $bill->appointment_id);
                                        $set('received_by', auth()->id());
                                        $set('payment_date', now()->format('Y-m-d'));
                                        
                                        // Calculate expected payment amount
                                        if ($bill->payment_type === 'staggered') {
                                            if (!$bill->isDownPaymentMade()) {
                                                $set('amount', $bill->down_payment);
                                            } else {
                                                $set('amount', $bill->getNextPaymentAmount());
                                            }
                                        } else {
                                            $set('amount', $bill->remaining_balance);
                                        }
                                        
                                        // Auto-fill payment method and reference
                                        $set('payment_method', 'cash');
                                        
                                        // Auto-fill payment reference based on payment type
                                        if ($bill->payment_type === 'staggered') {
                                            if (!$bill->isDownPaymentMade()) {
                                                $set('payment_reference', 'Down Payment - ' . $bill->bill_number);
                                            } else {
                                                $installmentNum = $bill->getNextInstallmentNumber() - 1;
                                                $set('payment_reference', "Installment #{$installmentNum} - " . $bill->bill_number);
                                            }
                                        } else {
                                            $set('payment_reference', 'Full Payment - ' . $bill->bill_number);
                                        }
                                    }
                                }
                            }),
                        Forms\Components\Hidden::make('client_id')
                            ->default(function () {
                                $billId = request()->get('bill_id');
                                if ($billId) {
                                    $bill = \App\Models\Bill::find($billId);
                                    return $bill ? $bill->client_id : null;
                                }
                                return null;
                            }),
                        Forms\Components\Hidden::make('appointment_id')
                            ->default(function () {
                                $billId = request()->get('bill_id');
                                if ($billId) {
                                    $bill = \App\Models\Bill::find($billId);
                                    return $bill ? $bill->appointment_id : null;
                                }
                                return null;
                            }),
                        Forms\Components\Hidden::make('received_by')
                            ->default(auth()->id()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->required()
                            ->prefix('₱')
                            ->live()
                            ->default(function () {
                                $billId = request()->get('bill_id');
                                if ($billId) {
                                    $bill = \App\Models\Bill::find($billId);
                                    if ($bill) {
                                        if ($bill->payment_type === 'staggered') {
                                            if (!$bill->isDownPaymentMade()) {
                                                return $bill->down_payment;
                                            } else {
                                                return $bill->getNextPaymentAmount();
                                            }
                                        } else {
                                            return $bill->remaining_balance;
                                        }
                                    }
                                }
                                return null;
                            })
                            ->helperText(function (Forms\Get $get) {
                                $billId = $get('bill_id');
                                if (!$billId) return '';
                                
                                $bill = \App\Models\Bill::find($billId);
                                if (!$bill) return '';
                                
                                if ($bill->payment_type === 'staggered') {
                                    if (!$bill->isDownPaymentMade()) {
                                        return "Down payment expected: ₱" . number_format($bill->down_payment, 2);
                                    } else {
                                        $installmentNum = $bill->getNextInstallmentNumber() - 1;
                                        return "Installment #$installmentNum expected: ₱" . number_format($bill->installment_amount, 2);
                                    }
                                }
                                
                                return "Full payment expected: ₱" . number_format($bill->remaining_balance, 2);
                            }),
                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'check' => 'Check',
                                'bank_transfer' => 'Bank Transfer',
                                'card' => 'Card',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->default('cash'),
                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Payment Reference')
                            ->placeholder('Check #, Transaction #, etc.')
                            ->maxLength(255)
                            ->default(function () {
                                $billId = request()->get('bill_id');
                                if ($billId) {
                                    $bill = \App\Models\Bill::find($billId);
                                    if ($bill) {
                                        if ($bill->payment_type === 'staggered') {
                                            if (!$bill->isDownPaymentMade()) {
                                                return 'Down Payment - ' . $bill->bill_number;
                                            } else {
                                                $installmentNum = $bill->getNextInstallmentNumber() - 1;
                                                return "Installment #{$installmentNum} - " . $bill->bill_number;
                                            }
                                        } else {
                                            return 'Full Payment - ' . $bill->bill_number;
                                        }
                                    }
                                }
                                return 'Cash Payment';
                            }),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->required()
                            ->default(now()->format('Y-m-d'))
                            ->native(false),
                    ])
                    ->columns(2),


                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Payment Notes')
                            ->rows(3)
                            ->placeholder('Additional notes about this payment...'),
                        Forms\Components\Textarea::make('transaction_details')
                            ->label('Transaction Details')
                            ->rows(3)
                            ->placeholder('Detailed transaction information...'),
                        Forms\Components\Select::make('status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('completed'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['bill', 'client', 'appointment.service', 'receivedBy']))
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->label('Payment #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('bill.bill_number')
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
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'check' => 'warning',
                        'bank_transfer' => 'info',
                        'card' => 'primary',
                        'other' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_reference')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receivedBy.name')
                    ->label('Received By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Processed')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'check' => 'Check',
                        'bank_transfer' => 'Bank Transfer',
                        'card' => 'Card',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->label('Today\'s Payments')
                    ->query(fn (Builder $query): Builder => $query->whereDate('payment_date', now()->toDateString()))
                    ->toggle(),
                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_receipt')
                    ->label('Print Receipt')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('staff.payment.print', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('process')
                    ->label('Process')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->process();
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),
                Tables\Actions\Action::make('view_balance')
                    ->label('View Balance')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('info')
                    ->modalContent(fn ($record) => view('filament.staff.modals.balance-details', ['bill' => $record->bill]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                //
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
