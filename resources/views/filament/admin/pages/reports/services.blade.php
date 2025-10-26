<!-- Services Report Summary -->
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-beaker class="w-5 h-5" />
                Services Summary
            </div>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Services</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                {{ number_format($reportData['summary']['total_services']) }}
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-green-600 dark:text-green-400">Services Used</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                {{ number_format($reportData['summary']['used_services']) }}
            </div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Utilization Rate</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300 mt-1">
                {{ number_format($reportData['summary']['utilization_rate'], 1) }}%
            </div>
        </div>
    </div>
</x-filament::section>

<!-- Service Performance -->
<x-filament::section>
    <x-slot name="heading">
        Complete Service Performance
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Service</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Price</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Bookings</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Completed</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['service_performance'] as $service)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">{{ $service->service_name }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">
                            ₱{{ number_format($service->price, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($service->bookings) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($service->completed) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-purple-600 dark:text-purple-400">
                            ₱{{ number_format($service->revenue ?? 0, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::section>

<!-- Top 5 Most Popular Services -->
<x-filament::section>
    <x-slot name="heading">
        🏆 Top 5 Most Popular Services
    </x-slot>

    <div class="space-y-3">
        @foreach($reportData['popular_services']->take(5) as $index => $service)
            <div class="flex items-center justify-between p-4 border dark:border-gray-700 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <span class="text-sm font-bold text-blue-700 dark:text-blue-300">#{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $service->service_name }}</div>
                        <div class="text-sm text-gray-500">{{ $service->completed }} completed</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $service->bookings }}</div>
                    <div class="text-xs text-gray-500">bookings</div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>

<!-- Top 5 Highest Revenue Services -->
<x-filament::section>
    <x-slot name="heading">
        💰 Top 5 Highest Revenue Services
    </x-slot>

    <div class="space-y-3">
        @foreach($reportData['highest_revenue']->take(5) as $index => $service)
            <div class="flex items-center justify-between p-4 border dark:border-gray-700 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <span class="text-sm font-bold text-green-700 dark:text-green-300">#{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $service->service_name }}</div>
                        <div class="text-sm text-gray-500">{{ $service->bookings }} bookings</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-green-600 dark:text-green-400">
                        ₱{{ number_format($service->revenue ?? 0, 2) }}
                    </div>
                    <div class="text-xs text-gray-500">revenue</div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>

<!-- Services by Category -->
@if($reportData['by_category']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Services by Category
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($reportData['by_category'] as $category)
            <div class="border dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">{{ $category->category ?? 'Uncategorized' }}</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $category->count }}</span>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>
@endif

