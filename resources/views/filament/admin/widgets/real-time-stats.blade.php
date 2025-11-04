<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Today's Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Appointments</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayAppointments }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Completed -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Today</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayCompleted }}</p>
                </div>
            </div>
        </div>

        <!-- Online Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Online Users</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $onlineUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingAppointments }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentAppointments as $appointment)
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                {{ strtoupper(substr($appointment->client->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $appointment->client->name }} - {{ $appointment->service?->service_name ?? 'No Service' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($appointment->status === 'completed') bg-green-100 text-green-800
                            @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No appointments have been updated recently.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Real-time JavaScript -->
    <script>
        // Enable real-time updates using Laravel Echo
        if (typeof Echo !== 'undefined') {
            // Listen for appointment status updates
            Echo.private('appointment.{{ auth()->id() }}')
                .listen('AppointmentStatusUpdated', (e) => {
                    // Update the UI with new appointment status
                    updateAppointmentStatus(e.appointment_id, e.new_status);
                    
                    // Show notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Appointment Updated',
                            text: `Appointment status changed to ${e.new_status}`,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });

            // Listen for online user updates
            Echo.channel('online-users')
                .listen('UserOnlineStatusChanged', (e) => {
                    updateOnlineUsersCount();
                });
        }

        function updateAppointmentStatus(appointmentId, newStatus) {
            // Find and update the appointment status in the recent activity
            const statusElement = document.querySelector(`[data-appointment-id="${appointmentId}"] .status-badge`);
            if (statusElement) {
                statusElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                statusElement.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    newStatus === 'completed' ? 'bg-green-100 text-green-800' :
                    newStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                    newStatus === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                    'bg-red-100 text-red-800'
                }`;
            }
        }

        function updateOnlineUsersCount() {
            // Refresh the online users count
            fetch('/api/admin/online-users-count')
                .then(response => response.json())
                .then(data => {
                    const onlineUsersElement = document.querySelector('[data-stat="online-users"]');
                    if (onlineUsersElement) {
                        onlineUsersElement.textContent = data.count;
                    }
                });
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</x-filament-widgets::widget>
