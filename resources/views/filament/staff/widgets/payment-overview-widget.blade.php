<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Payment Overview
        </x-slot>
        
        <x-slot name="description">
            Comprehensive view of payment statistics and trends
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Statistics -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Payment Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $this->getViewData()['totalBills'] }}</div>
                        <div class="text-sm text-blue-600">Total Bills</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $this->getViewData()['paidBills'] }}</div>
                        <div class="text-sm text-green-600">Paid Bills</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $this->getViewData()['pendingBills'] }}</div>
                        <div class="text-sm text-yellow-600">Pending Bills</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $this->getViewData()['staggeredBills'] }}</div>
                        <div class="text-sm text-purple-600">Staggered Bills</div>
                    </div>
                </div>
            </div>

            <!-- Financial Overview -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Financial Overview</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Total Amount</span>
                        <span class="text-lg font-bold text-gray-900">₱{{ number_format($this->getViewData()['totalAmount'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium text-green-600">Paid Amount</span>
                        <span class="text-lg font-bold text-green-600">₱{{ number_format($this->getViewData()['paidAmount'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-red-600">Remaining Balance</span>
                        <span class="text-lg font-bold text-red-600">₱{{ number_format($this->getViewData()['remainingAmount'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        @if($this->getViewData()['recentPayments']->count() > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Payments</h3>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($this->getViewData()['recentPayments'] as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $payment->payment_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->bill->client->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->bill->appointment->service->service_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->status === 'completed') bg-green-100 text-green-800
                                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Active Staggered Payments -->
        @if($this->getViewData()['activeStaggeredPayments']->count() > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Staggered Payments</h3>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($this->getViewData()['activeStaggeredPayments'] as $bill)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $bill->client->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bill->appointment->service->service_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format($bill->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    ₱{{ number_format($bill->paid_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                    ₱{{ number_format($bill->remaining_balance, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $progressPercentage = $bill->total_amount > 0 ? ($bill->paid_amount / $bill->total_amount) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ number_format($progressPercentage, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>