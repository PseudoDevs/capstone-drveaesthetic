<!-- Staff Report Summary -->
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-user-group class="w-5 h-5" />
                Staff Performance Summary
            </div>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Staff</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                {{ number_format($reportData['summary']['total_staff']) }}
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-green-600 dark:text-green-400">Active Staff</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                {{ number_format($reportData['summary']['active_staff']) }}
            </div>
            <div class="text-xs text-green-600 dark:text-green-400 mt-1">Handled appointments in this period</div>
        </div>
    </div>
</x-filament::section>

<!-- Staff Performance -->
<x-filament::section>
    <x-slot name="heading">
        Staff Performance Overview
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Staff</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Email</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Appointments</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Completed</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Revenue</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Avg Rating</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['staff_performance'] as $staff)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">{{ $staff->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $staff->email }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($staff->total_appointments) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($staff->completed) }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-purple-600 dark:text-purple-400">
                            ₱{{ number_format($staff->revenue ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($staff->avg_rating)
                                <div class="flex items-center justify-end gap-1">
                                    <span class="font-semibold text-yellow-600 dark:text-yellow-400">
                                        {{ number_format($staff->avg_rating, 1) }}
                                    </span>
                                    <x-heroicon-o-star class="w-4 h-4 text-yellow-500" />
                                </div>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::section>

<!-- Top Performers -->
@if($reportData['staff_performance']->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Most Appointments -->
    <x-filament::section>
        <x-slot name="heading">
            🏆 Most Appointments
        </x-slot>

        @php
            $topByAppointments = $reportData['staff_performance']->sortByDesc('total_appointments')->take(3);
        @endphp

        <div class="space-y-3">
            @foreach($topByAppointments as $index => $staff)
                <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full 
                            @if($index === 0) bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                            @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                            @else bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300
                            @endif
                            flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-sm font-medium">{{ $staff->name }}</div>
                    </div>
                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                        {{ $staff->total_appointments }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    <!-- Highest Revenue -->
    <x-filament::section>
        <x-slot name="heading">
            💰 Highest Revenue
        </x-slot>

        @php
            $topByRevenue = $reportData['staff_performance']->sortByDesc('revenue')->take(3);
        @endphp

        <div class="space-y-3">
            @foreach($topByRevenue as $index => $staff)
                <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full 
                            @if($index === 0) bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                            @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                            @else bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300
                            @endif
                            flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-sm font-medium">{{ $staff->name }}</div>
                    </div>
                    <div class="text-sm font-bold text-green-600 dark:text-green-400">
                        ₱{{ number_format($staff->revenue ?? 0, 2) }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    <!-- Highest Rating -->
    <x-filament::section>
        <x-slot name="heading">
            ⭐ Highest Rating
        </x-slot>

        @php
            $topByRating = $reportData['staff_performance']->filter(fn($s) => $s->avg_rating !== null)->sortByDesc('avg_rating')->take(3);
        @endphp

        <div class="space-y-3">
            @forelse($topByRating as $index => $staff)
                <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full 
                            @if($index === 0) bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                            @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                            @else bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300
                            @endif
                            flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-sm font-medium">{{ $staff->name }}</div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($staff->avg_rating, 1) }}
                        </span>
                        <x-heroicon-o-star class="w-4 h-4 text-yellow-500" />
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-gray-500 text-sm">No ratings available</div>
            @endforelse
        </div>
    </x-filament::section>
</div>
@endif

<!-- Prescriptions Issued -->
@if($reportData['prescriptions_by_staff']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Prescriptions Issued by Staff
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Staff</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Prescriptions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reportData['prescriptions_by_staff'] as $staff)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $staff->name }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($staff->count) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

<!-- Medical Certificates Issued -->
@if($reportData['certificates_by_staff']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Medical Certificates Issued by Staff
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Staff</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Certificates</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reportData['certificates_by_staff'] as $staff)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $staff->name }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-teal-600 dark:text-teal-400">
                            {{ number_format($staff->count) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

