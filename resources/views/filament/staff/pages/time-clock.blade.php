<x-filament-panels::page>

    {{-- Main Content Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Side: Time Clock Section --}}
        <div class="lg:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-o-clock" class="h-6 w-6 text-primary-500" />
                        Current Time
                    </div>
                </x-slot>

                <div class="text-center space-y-6">
                    {{-- Current Date --}}
                    <x-filament::badge color="primary" size="lg">
                        {{ now()->format('l, F j, Y') }}
                    </x-filament::badge>

                    {{-- Live Clock --}}
                    <div class="text-5xl lg:text-6xl font-mono font-bold text-primary-600 dark:text-primary-400"
                        x-data="{
                            time: '{{ now()->format('H:i:s') }}',
                            updateTime() {
                                const now = new Date();
                                this.time = now.toLocaleTimeString('en-US', {
                                    hour12: false,
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }
                        }" x-init="setInterval(() => updateTime(), 1000)" x-text="time">
                    </div>
                </div>
            </x-filament::section>

            {{-- Clock Actions --}}
            <div class="mt-6 flex gap-3">
                {{-- Clock In Button --}}
                <x-filament::button wire:click="clockIn" outlined color="success" size="lg" 
                    :disabled="$todayLog !== null" class="flex-1 justify-center">
                    Clock In
                </x-filament::button>

                {{-- Clock Out Button --}}
                @if($todayLog && $todayLog->isActive())
                    {{ ($this->clockOutAction)(['outlined' => true, 'size' => 'lg', 'class' => 'flex-1 justify-center']) }}
                @else
                    <x-filament::button outlined color="danger" size="lg" disabled class="flex-1 justify-center">
                        Clock Out
                    </x-filament::button>
                @endif
            </div>

            {{-- Status Message --}}
            <div class="mt-6 text-center">
                @if (!$todayLog)
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                        Ready to start
                    </span>
                @elseif($todayLog->isActive())
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Working
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-900 dark:text-gray-300">
                        Completed
                    </span>
                @endif
            </div>
        </div>

        {{-- Middle: Session Details --}}
        <div class="lg:col-span-1">
            @if ($todayLog)
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-3">
                            <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6 text-primary-500" />
                            Today's Session
                        </div>
                    </x-slot>

                    @if ($todayLog->isActive())
                        <div class="space-y-4">
                            <div class="text-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                                <div class="text-sm text-primary-600 dark:text-primary-400">Clock In</div>
                                <div class="text-2xl font-bold text-primary-900 dark:text-primary-300">
                                    {{ $todayLog->clock_in->format('H:i') }}</div>
                            </div>
                            <div class="text-center p-4 bg-warning-50 dark:bg-warning-900/20 rounded-xl">
                                <div class="text-sm text-warning-600 dark:text-warning-400">Working Hours</div>
                                <div class="text-2xl font-bold text-warning-900 dark:text-warning-300"
                                    x-data="{
                                        hours: 0,
                                        updateHours() {
                                            const clockIn = new Date('{{ $todayLog->clock_in }}');
                                            const now = new Date();
                                            const diff = (now - clockIn) / (1000 * 60 * 60);
                                            this.hours = diff.toFixed(2);
                                        }
                                    }" x-init="updateHours();
                                    setInterval(() => updateHours(), 1000)" x-text="hours + ' hrs'">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                                <div class="text-xs text-primary-600 dark:text-primary-400">In</div>
                                <div class="font-bold text-primary-900 dark:text-primary-300">
                                    {{ $todayLog->clock_in->format('H:i') }}</div>
                            </div>
                            <div class="p-3 bg-danger-50 dark:bg-danger-900/20 rounded-xl">
                                <div class="text-xs text-danger-600 dark:text-danger-400">Out</div>
                                <div class="font-bold text-danger-900 dark:text-danger-300">
                                    {{ $todayLog->clock_out->format('H:i') }}</div>
                            </div>
                            <div class="p-3 bg-success-50 dark:bg-success-900/20 rounded-xl">
                                <div class="text-xs text-success-600 dark:text-success-400">Total</div>
                                <div class="font-bold text-success-900 dark:text-success-300">
                                    {{ number_format($todayLog->total_hours, 1) }}h</div>
                            </div>
                        </div>
                    @endif
                </x-filament::section>
            @else
                <x-filament::section>
                    <x-slot name="heading">No Session</x-slot>
                    <div class="text-center py-8">
                        <x-filament::icon icon="heroicon-o-clock" class="mx-auto h-16 w-16 text-gray-400 mb-4" />
                        <p class="text-gray-600 dark:text-gray-400">Click "Clock In" to start</p>
                    </div>
                </x-filament::section>
            @endif
        </div>

        {{-- Right: Quick Overview --}}
        <div class="lg:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-o-chart-bar" class="h-6 w-6 text-primary-500" />
                        Overview
                    </div>
                </x-slot>

                <div class="space-y-4">
                    <div class="text-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                        <div class="text-sm text-primary-600 dark:text-primary-400">Status</div>
                        <div class="font-bold text-primary-900 dark:text-primary-300">
                            @if (!$todayLog)
                                Not Started
                            @elseif($todayLog->isActive())
                                In Progress
                            @else
                                Completed
                            @endif
                        </div>
                    </div>

                    @if ($todayLog)
                        <div class="text-center p-4 bg-success-50 dark:bg-success-900/20 rounded-xl">
                            <div class="text-sm text-success-600 dark:text-success-400">Hours Today</div>
                            <div class="font-bold text-success-900 dark:text-success-300"
                                @if ($todayLog->isActive()) x-data="{
                                    hours: 0,
                                    updateHours() {
                                        const clockIn = new Date('{{ $todayLog->clock_in }}');
                                        const now = new Date();
                                        const diff = (now - clockIn) / (1000 * 60 * 60);
                                        this.hours = diff.toFixed(1);
                                    }
                                }" x-init="updateHours(); setInterval(() => updateHours(), 1000)" x-text="hours + ' hrs'" @endif>
                                @if (!$todayLog->isActive())
                                    {{ number_format($todayLog->total_hours, 1) }} hrs
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="text-center p-4 bg-info-50 dark:bg-info-900/20 rounded-xl">
                        <div class="text-sm text-info-600 dark:text-info-400">Date</div>
                        <div class="font-bold text-info-900 dark:text-info-300">{{ now()->format('M j') }}</div>
                    </div>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
