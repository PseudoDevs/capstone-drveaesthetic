<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-currency-dollar class="h-5 w-5 text-primary-500" />
                Payment Overview
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Bills -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bills</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBills }}</p>
                    </div>
                    <x-heroicon-o-receipt-percent class="h-8 w-8 text-blue-500" />
                </div>
            </div>

            <!-- Paid Bills -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Paid Bills</p>
                        <p class="text-2xl font-bold text-green-600">{{ $paidBills }}</p>
                    </div>
                    <x-heroicon-o-check-circle class="h-8 w-8 text-green-500" />
                </div>
            </div>

            <!-- Pending Bills -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Bills</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $pendingBills }}</p>
                    </div>
                    <x-heroicon-o-clock class="h-8 w-8 text-orange-500" />
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Amount</p>
                        <p class="text-xl font-bold text-blue-900 dark:text-blue-100">₱{{ number_format($totalAmount, 2) }}</p>
                    </div>
                    <x-heroicon-o-banknotes class="h-6 w-6 text-blue-500" />
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Paid Amount</p>
                        <p class="text-xl font-bold text-green-900 dark:text-green-100">₱{{ number_format($paidAmount, 2) }}</p>
                    </div>
                    <x-heroicon-o-check-circle class="h-6 w-6 text-green-500" />
                </div>
            </div>

            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Remaining Balance</p>
                        <p class="text-xl font-bold text-orange-900 dark:text-orange-100">₱{{ number_format($remainingAmount, 2) }}</p>
                    </div>
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-orange-500" />
                </div>
            </div>
        </div>

        <!-- Staggered Payments -->
        @if($staggeredBills->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Staggered Payments</h3>
            <div class="space-y-4">
                @foreach($staggeredBills as $bill)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $bill->appointment->service->service_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bill #{{ $bill->bill_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">₱{{ number_format($bill->total_amount, 2) }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $bill->getPaidInstallmentsCount() }}/{{ $bill->total_installments }} installments</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($bill->getPaidInstallmentsCount() / $bill->total_installments) * 100 }}%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">
                            Next payment: ₱{{ number_format($bill->installment_amount, 2) }}
                        </span>
                        <span class="text-gray-600 dark:text-gray-400">
                            Due: {{ $bill->getNextDueDate() ? $bill->getNextDueDate()->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Payments -->
        @if($recentPayments->count() > 0)
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Payments</h3>
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $payment->bill->appointment->service->service_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payment->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-green-600">₱{{ number_format($payment->amount, 2) }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $payment->payment_method }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
