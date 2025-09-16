@php
    $data = $getState() ?? [];
@endphp

@if(!empty($data))
<div class="space-y-4">
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Consent & Waiver Information</h4>

        <div class="space-y-3">
            @if(isset($data['treatment_consent']))
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0">
                        @if($data['treatment_consent'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                ✓ Agreed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                ✗ Not Agreed
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Consent</span>
                </div>
            @endif

            @if(isset($data['photo_consent']))
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0">
                        @if($data['photo_consent'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                ✓ Agreed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                ✗ Not Agreed
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Photo/Video Consent</span>
                </div>
            @endif

            @if(isset($data['marketing_consent']))
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0">
                        @if($data['marketing_consent'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                ✓ Agreed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                ✗ Declined
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Marketing Communications</span>
                </div>
            @endif

            @if(isset($data['liability_waiver']))
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0">
                        @if($data['liability_waiver'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                ✓ Agreed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                ✗ Not Agreed
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Liability Waiver</span>
                </div>
            @endif

            @if(isset($data['terms_and_conditions']))
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0">
                        @if($data['terms_and_conditions'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                ✓ Agreed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                ✗ Not Agreed
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Terms & Conditions</span>
                </div>
            @endif
        </div>

        @if(isset($data['client_signature']))
            <div class="mt-4 pt-3 border-t border-blue-200 dark:border-blue-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(isset($data['client_signature']['name']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Client Signature:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $data['client_signature']['name'] }}</p>
                        </div>
                    @endif
                    @if(isset($data['client_signature']['date']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Signed:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($data['client_signature']['date'])->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if(isset($data['witness_signature']))
            <div class="mt-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(isset($data['witness_signature']['name']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Witness:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $data['witness_signature']['name'] }}</p>
                        </div>
                    @endif
                    @if(isset($data['witness_signature']['date']))
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Witness Date:</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($data['witness_signature']['date'])->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if(isset($data['special_instructions']) && !empty($data['special_instructions']))
            <div class="mt-4">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Special Instructions:</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 p-2 rounded border mt-1">{{ $data['special_instructions'] }}</p>
            </div>
        @endif

        @if(isset($data['submitted_at']))
            <div class="mt-4 pt-3 border-t border-blue-200 dark:border-blue-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Consent Form Submitted: {{ \Carbon\Carbon::parse($data['submitted_at'])->format('M d, Y \a\t h:i A') }}
                </p>
            </div>
        @endif
    </div>
</div>
@else
    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
        <p>No consent/waiver form data available</p>
    </div>
@endif