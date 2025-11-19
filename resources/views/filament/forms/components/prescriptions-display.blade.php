@php
    $prescriptions = $getRecord()->prescriptions()->with(['prescribedBy'])->get();
@endphp

@if($prescriptions->count() > 0)
    <div class="space-y-4">
        @foreach($prescriptions as $prescription)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-lg">{{ $prescription->medication_name }}</h4>
                            <p class="text-sm text-gray-500">
                                Prescribed on {{ $prescription->prescribed_date->format('M d, Y') }}
                                by {{ $prescription->prescribedBy->name ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dosage</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $prescription->dosage }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Frequency</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $prescription->frequency }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Duration</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $prescription->duration }}</div>
                    </div>
                </div>
                
                @if($prescription->instructions)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Special Instructions</h3>
                                <div class="mt-1 text-sm text-blue-700">{{ $prescription->instructions }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($prescription->notes)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Doctor's Notes</h3>
                                <div class="mt-1 text-sm text-yellow-700">{{ $prescription->notes }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No prescriptions</h3>
        <p class="mt-1 text-sm text-gray-500">No prescriptions have been added for this appointment yet.</p>
    </div>
@endif

























