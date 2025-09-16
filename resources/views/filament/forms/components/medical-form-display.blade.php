@php
    $data = $getState() ?? [];
@endphp

@if(!empty($data))
<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Medical Information</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(isset($data['medical_conditions']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Medical Conditions:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['medical_conditions'] ?: 'None specified' }}</p>
                </div>
            @endif

            @if(isset($data['current_medications']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Medications:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['current_medications'] ?: 'None specified' }}</p>
                </div>
            @endif

            @if(isset($data['allergies']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Allergies:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['allergies'] ?: 'None specified' }}</p>
                </div>
            @endif

            @if(isset($data['previous_surgeries']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Previous Surgeries:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['previous_surgeries'] ?: 'None specified' }}</p>
                </div>
            @endif

            @if(isset($data['pregnancy_status']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pregnancy Status:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['pregnancy_status'] ?: 'Not specified' }}</p>
                </div>
            @endif

            @if(isset($data['skin_type']))
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Skin Type:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['skin_type'] ?: 'Not specified' }}</p>
                </div>
            @endif
        </div>

        @if(isset($data['additional_notes']) && !empty($data['additional_notes']))
            <div class="mt-4">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes:</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 p-2 rounded border">{{ $data['additional_notes'] }}</p>
            </div>
        @endif

        @if(isset($data['emergency_contact']))
            <div class="mt-4">
                <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Emergency Contact</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @if(isset($data['emergency_contact']['name']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Name:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['emergency_contact']['name'] }}</p>
                        </div>
                    @endif
                    @if(isset($data['emergency_contact']['phone']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['emergency_contact']['phone'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if(isset($data['submitted_at']))
            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Submitted: {{ \Carbon\Carbon::parse($data['submitted_at'])->format('M d, Y \a\t h:i A') }}
                </p>
            </div>
        @endif
    </div>
</div>
@else
    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
        <p>No medical form data available</p>
    </div>
@endif