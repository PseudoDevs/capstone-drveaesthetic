<div class="p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Balance Details</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Bill #{{ $bill->bill_number }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bill Information -->
        <div class="space-y-4">
            <h4 class="font-medium text-gray-900 dark:text-gray-100">Bill Information</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</span>
                    <span class="text-sm font-medium dark:text-gray-100">₱{{ number_format($bill->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Paid Amount:</span>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400">₱{{ number_format($bill->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Remaining Balance:</span>
                    <span class="text-sm font-medium text-red-600 dark:text-red-400">₱{{ number_format($bill->remaining_balance, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Payment Type:</span>
                    <span class="text-sm font-medium dark:text-gray-100">
                        {{ ucfirst($bill->payment_type) }}
                        @if($bill->payment_type === 'staggered')
                            ({{ $bill->total_installments ?? 'N/A' }} installments)
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Progress -->
        <div class="space-y-4">
            <h4 class="font-medium text-gray-900 dark:text-gray-100">Payment Progress</h4>
            <div class="space-y-2">
                @php
                    $progressPercentage = $bill->total_amount > 0 ? ($bill->paid_amount / $bill->total_amount) * 100 : 0;
                @endphp
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($progressPercentage, 1) }}% Complete</p>
                
                @if($bill->payment_type === 'staggered')
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Installment Details:</p>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm dark:text-gray-300">
                                <span>Down Payment:</span>
                                <span>₱{{ number_format($bill->down_payment ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm dark:text-gray-300">
                                <span>Installment Amount:</span>
                                <span>₱{{ number_format($bill->installment_amount ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm dark:text-gray-300">
                                <span>Payments Made:</span>
                                <span>{{ $bill->payments->count() }}/{{ $bill->total_installments ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    @if($bill->payments->count() > 0)
    <div class="mt-6">
        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Recent Payments</h4>
        <div class="space-y-2 max-h-40 overflow-y-auto">
            @foreach($bill->payments->take(5) as $payment)
            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-800 rounded">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($payment->amount, 2) }}</p>
                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ $payment->payment_date->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                        @if($payment->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($payment->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
