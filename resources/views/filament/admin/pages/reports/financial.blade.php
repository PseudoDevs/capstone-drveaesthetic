<!-- Financial Report Summary -->
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-currency-dollar class="w-5 h-5" />
                Financial Summary
            </div>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-green-600 dark:text-green-400">Total Revenue</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                ₱{{ number_format($reportData['summary']['total_revenue'], 2) }}
            </div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-red-600 dark:text-red-400">Outstanding</div>
            <div class="text-2xl font-bold text-red-700 dark:text-red-300 mt-1">
                ₱{{ number_format($reportData['summary']['outstanding_balance'], 2) }}
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Bills</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                {{ number_format($reportData['summary']['total_bills']) }}
            </div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Total Payments</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300 mt-1">
                {{ number_format($reportData['summary']['total_payments']) }}
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Bill</div>
            <div class="text-2xl font-bold text-gray-700 dark:text-gray-300 mt-1">
                ₱{{ number_format($reportData['summary']['average_bill'], 2) }}
            </div>
        </div>
    </div>
</x-filament::section>

<!-- Revenue by Service -->
<x-filament::section>
    <x-slot name="heading">
        Revenue by Service
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Service</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['revenue_by_service'] as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->service_name }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                            ₱{{ number_format($item->revenue, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::section>

<!-- Payment Methods -->
<x-filament::section>
    <x-slot name="heading">
        Payment Methods Breakdown
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($reportData['payment_methods'] as $method)
            <div class="border dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium capitalize">{{ str_replace('_', ' ', $method->payment_method) }}</span>
                    <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ $method->count }}x</span>
                </div>
                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    ₱{{ number_format($method->total, 2) }}
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>

<!-- Payment Types -->
<x-filament::section>
    <x-slot name="heading">
        Payment Types Distribution
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($reportData['payment_types'] as $type)
            <div class="border dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium capitalize">
                        @if($type->payment_type === 'full')
                            Full Payment
                        @else
                            Staggered Payment (Installments)
                        @endif
                    </span>
                    <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ $type->count }} bills</span>
                </div>
                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    ₱{{ number_format($type->total, 2) }}
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>

<!-- Bills by Status -->
<x-filament::section>
    <x-slot name="heading">
        Bills by Status
    </x-slot>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($reportData['bills_by_status'] as $status)
            <div class="text-center p-4 border dark:border-gray-700 rounded-lg">
                <div class="text-2xl font-bold 
                    @if($status->status === 'paid') text-green-600 
                    @elseif($status->status === 'partial') text-blue-600
                    @elseif($status->status === 'pending') text-yellow-600
                    @elseif($status->status === 'overdue') text-red-600
                    @else text-gray-600 
                    @endif
                ">
                    {{ $status->count }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 capitalize">{{ $status->status }}</div>
            </div>
        @endforeach
    </div>
</x-filament::section>

