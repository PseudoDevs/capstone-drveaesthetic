<!-- Clients Report Summary -->
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-users class="w-5 h-5" />
                Clients Summary
            </div>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Clients</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                {{ number_format($reportData['summary']['total_clients']) }}
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-green-600 dark:text-green-400">New Clients</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                {{ number_format($reportData['summary']['new_clients']) }}
            </div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Active Clients</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300 mt-1">
                {{ number_format($reportData['summary']['active_clients']) }}
            </div>
        </div>

        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Returning</div>
            <div class="text-2xl font-bold text-indigo-700 dark:text-indigo-300 mt-1">
                {{ number_format($reportData['summary']['returning_clients']) }}
            </div>
        </div>

        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Retention Rate</div>
            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">
                {{ number_format($reportData['summary']['retention_rate'], 1) }}%
            </div>
        </div>
    </div>
</x-filament::section>

<!-- Top 10 Clients by Revenue -->
<x-filament::section>
    <x-slot name="heading">
        👑 Top 10 Clients by Revenue
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Rank</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Client</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Email</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Total Spent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['top_clients'] as $index => $client)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="w-8 h-8 rounded-full 
                                @if($index === 0) bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                                @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                @elseif($index === 2) bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300
                                @else bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400
                                @endif
                                flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">{{ $client->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $client->email }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                            ₱{{ number_format($client->total_spent, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::section>

<!-- Client Demographics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- By Gender -->
    <x-filament::section>
        <x-slot name="heading">
            Clients by Gender
        </x-slot>

        <div class="space-y-3">
            @foreach($reportData['by_gender'] as $gender)
                <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg">
                    <span class="text-sm font-medium capitalize">{{ $gender->gender }}</span>
                    <div class="flex items-center gap-3">
                        <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $gender->count }}</div>
                        <div class="text-xs text-gray-500">
                            ({{ $reportData['summary']['total_clients'] > 0 ? round(($gender->count / $reportData['summary']['total_clients']) * 100, 1) : 0 }}%)
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    <!-- By Age Group -->
    <x-filament::section>
        <x-slot name="heading">
            Clients by Age Group
        </x-slot>

        <div class="space-y-3">
            @foreach($reportData['by_age'] as $age)
                <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg">
                    <span class="text-sm font-medium">{{ $age->age_group }}</span>
                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $age->count }}</div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</div>

<!-- Client Acquisition Trend -->
@if($reportData['acquisition']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Client Acquisition Trend
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Month</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">New Clients</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reportData['acquisition'] as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($item->month . '-01')->format('F Y') }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                            {{ $item->count }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

