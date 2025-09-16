@php
    $record = $getRecord();
@endphp

<div class="text-sm">
    @if(!$record->form_completed)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-3">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-yellow-800 dark:text-yellow-200">Pre-assessment not completed</span>
            </div>
            <p class="mt-2 text-yellow-700 dark:text-yellow-300">Client needs to fill out their medical forms before the appointment.</p>
        </div>
    @else
        @php
            $medicalCompleted = !empty($record->medical_form_data);
            $consentCompleted = !empty($record->consent_waiver_form_data);
        @endphp

        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-3">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-green-800 dark:text-green-200">Pre-assessment completed</span>
            </div>

            <div class="space-y-2">
                <div class="flex items-center">
                    @if($medicalCompleted)
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 dark:text-green-300">Medical Form: Completed</span>
                    @else
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 dark:text-red-300">Medical Form: Not Completed</span>
                    @endif
                </div>

                <div class="flex items-center">
                    @if($consentCompleted)
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 dark:text-green-300">Consent Waiver: Completed</span>
                    @else
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 dark:text-red-300">Consent Waiver: Not Completed</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>