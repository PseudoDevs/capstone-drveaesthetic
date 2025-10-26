<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Report Selection Form -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-funnel class="w-5 h-5" />
                    Generate Report
                </div>
            </x-slot>

            <form wire:submit="generateReport">
                {{ $this->form }}

                <div class="mt-4 flex gap-3">
                    <x-filament::button type="submit" icon="heroicon-o-document-chart-bar">
                        Generate Report
                    </x-filament::button>

                    @if($reportData)
                        <x-filament::button
                            color="success"
                            icon="heroicon-o-arrow-down-tray"
                            wire:click="exportPDF"
                            tag="button"
                            type="button"
                        >
                            Export PDF
                        </x-filament::button>
                    @endif
                </div>
            </form>
        </x-filament::section>

        <!-- Report Results -->
        @if($reportData)
            <div class="space-y-6">
                @if($reportType === 'financial')
                    @include('filament.admin.pages.reports.financial')
                @elseif($reportType === 'appointments')
                    @include('filament.admin.pages.reports.appointments')
                @elseif($reportType === 'services')
                    @include('filament.admin.pages.reports.services')
                @elseif($reportType === 'clients')
                    @include('filament.admin.pages.reports.clients')
                @elseif($reportType === 'staff')
                    @include('filament.admin.pages.reports.staff')
                @endif
            </div>
        @else
            <x-filament::section>
                <div class="text-center py-12">
                    <x-heroicon-o-chart-bar class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        No Report Generated
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Select report type and date range, then click "Generate Report" to view data.
                    </p>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>

