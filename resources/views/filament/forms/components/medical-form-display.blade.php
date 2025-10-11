@php
    $record = $getRecord();
    $data = $record->medical_form_data ?? $getState() ?? [];
@endphp

@if(!empty($data))
<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Medical Information</h4>

        {{-- Medical History --}}
        @if(isset($data['medical_history']) && !empty($data['medical_history']))
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Medical History:</label>
            <div class="flex flex-wrap gap-2">
                @foreach($data['medical_history'] as $condition)
                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-md text-sm">
                        {{ ucfirst(str_replace('_', ' ', $condition)) }}
                    </span>
                @endforeach
            </div>
            @if(isset($data['medical_history_other']) && !empty($data['medical_history_other']))
                <div class="mt-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Other conditions:</label>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $data['medical_history_other'] }}</p>
                </div>
            @endif
        </div>
        @endif

        {{-- Maintenance Medications --}}
        @if(isset($data['maintenance_medications']) && !empty($data['maintenance_medications']))
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Medications:</label>
            <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 p-2 rounded border mt-1">{{ $data['maintenance_medications'] }}</p>
        </div>
        @endif

        {{-- Health Status Indicators --}}
        <div class="grid grid-cols-2 gap-4">
            @php
                $statusItems = [
                    'pregnant' => 'Pregnant',
                    'lactating' => 'Lactating',
                    'smoker' => 'Smoker',
                    'alcoholic_drinker' => 'Alcoholic Drinker'
                ];
            @endphp

            @foreach($statusItems as $key => $label)
                @if(isset($data[$key]))
                <div class="flex items-center">
                    @if($data[$key])
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 dark:text-red-300 text-sm font-medium">{{ $label }}</span>
                    @else
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 dark:text-green-300 text-sm"> {{ $label }}</span>
                    @endif
                </div>
                @endif
            @endforeach
        </div>

        {{-- Legacy form fields for backward compatibility --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
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