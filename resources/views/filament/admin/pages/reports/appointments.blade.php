<!-- Appointments Report Summary -->
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar class="w-5 h-5" />
                Appointments Summary
            </div>
            <span class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                {{ number_format($reportData['summary']['total_appointments']) }}
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-green-600 dark:text-green-400">Completed</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                {{ number_format($reportData['summary']['completed']) }}
            </div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-red-600 dark:text-red-400">Cancelled</div>
            <div class="text-2xl font-bold text-red-700 dark:text-red-300 mt-1">
                {{ number_format($reportData['summary']['cancelled']) }}
            </div>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-orange-600 dark:text-orange-400">No Show</div>
            <div class="text-2xl font-bold text-orange-700 dark:text-orange-300 mt-1">
                {{ number_format($reportData['summary']['no_show']) }}
            </div>
        </div>

        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Completion Rate</div>
            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">
                {{ number_format($reportData['summary']['completion_rate'], 1) }}%
            </div>
        </div>

        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4">
            <div class="text-sm font-medium text-rose-600 dark:text-rose-400">Cancellation Rate</div>
            <div class="text-2xl font-bold text-rose-700 dark:text-rose-300 mt-1">
                {{ number_format($reportData['summary']['cancellation_rate'], 1) }}%
            </div>
        </div>
    </div>
</x-filament::section>

<!-- Appointments by Status -->
<x-filament::section>
    <x-slot name="heading">
        Appointments by Status
    </x-slot>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($reportData['by_status'] as $status)
            <div class="text-center p-4 border dark:border-gray-700 rounded-lg">
                <div class="text-2xl font-bold 
                    @if($status->status === 'COMPLETED') text-green-600 
                    @elseif($status->status === 'PENDING') text-yellow-600
                    @elseif($status->status === 'CONFIRMED') text-blue-600
                    @elseif($status->status === 'CANCELLED') text-red-600
                    @else text-gray-600 
                    @endif
                ">
                    {{ $status->count }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $status->status }}</div>
            </div>
        @endforeach
    </div>
</x-filament::section>

<!-- Appointments by Service -->
<x-filament::section>
    <x-slot name="heading">
        Top Services by Bookings
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Service</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Bookings</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['by_service'] as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->service_name }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($item->count) }}
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

<!-- Appointments by Staff -->
<x-filament::section>
    <x-slot name="heading">
        Appointments by Staff
    </x-slot>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Staff</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Appointments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportData['by_staff'] as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->name }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-purple-600 dark:text-purple-400">
                            {{ number_format($item->count) }}
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

<!-- Appointments by Day of Week -->
@if($reportData['by_day']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Appointments by Day of Week
    </x-slot>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        @foreach($reportData['by_day'] as $day)
            <div class="text-center p-4 border dark:border-gray-700 rounded-lg">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $day->count }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $day->day }}</div>
            </div>
        @endforeach
    </div>
</x-filament::section>
@endif

<!-- Appointment Types -->
@if($reportData['by_type']->count() > 0)
<x-filament::section>
    <x-slot name="heading">
        Appointment Types
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($reportData['by_type'] as $type)
            <div class="border dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium capitalize">
                        @if($type->appointment_type === 'walk_in')
                            Walk-in
                        @elseif($type->appointment_type === 'online')
                            Online Booking
                        @else
                            {{ $type->appointment_type }}
                        @endif
                    </span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $type->count }}</span>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::section>
@endif

