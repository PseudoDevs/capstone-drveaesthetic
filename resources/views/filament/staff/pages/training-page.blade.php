<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Training & Guidelines</h1>
            <p class="text-blue-100">Access comprehensive training materials and guidelines to enhance your skills and
                knowledge.</p>
        </div>

        <!-- Filter Section -->
        <div class="flex flex-wrap gap-2 mb-6">
            <x-filament::button
                wire:click="clearFilter"
                size="sm"
                :color="$selectedType === '' ? 'primary' : 'gray'"
                :outlined="$selectedType !== ''">
                All Categories
            </x-filament::button>
            @foreach (['Safety Protocol', 'Customer Service', 'Technical Training', 'Policy Guidelines', 'Equipment Usage', 'Emergency Procedures', 'Quality Standards', 'Professional Development'] as $type)
                <x-filament::button
                    wire:click="filterByType('{{ $type }}')"
                    size="sm"
                    :color="$selectedType === $type ? 'primary' : 'gray'"
                    :outlined="$selectedType !== $type">
                    {{ $type }}
                </x-filament::button>
            @endforeach
        </div>

        <!-- Training Cards Grid -->
        <x-filament::section>
            @if($this->getTrainings()->count() > 0)
                <x-filament::grid
                    style="--cols-sm: repeat(2, minmax(0, 1fr)); --cols-lg: repeat(4, minmax(0, 1fr));"
                    class="grid-cols-1 sm:grid-cols-[--cols-sm] lg:grid-cols-[--cols-lg] gap-3">
                    @foreach($this->getTrainings() as $training)
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden group">
                    <!-- Thumbnail Image -->
                    <div class="p-4">
                        @if ($training->thumbnail)
                            <img 
                                src="{{ Storage::url($training->thumbnail) }}" 
                                alt="{{ $training->title }}"
                                class="w-full h-48 object-cover rounded-lg group-hover:scale-105 transition-transform duration-300"
                            />
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-academic-cap class="w-16 h-16 text-gray-400" />
                            </div>
                        @endif
                    </div>

                    <!-- Card Content -->
                    <div class="p-6 space-y-4">
                        <!-- Title -->
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $training->title }}
                        </h3>

                        <!-- Type Badge -->
                        @php
                            $badgeColor = match ($training->type) {
                                'Safety Protocol', 'Emergency Procedures' => 'danger',
                                'Customer Service', 'Quality Standards' => 'success',
                                'Technical Training' => 'info',
                                'Policy Guidelines' => 'warning',
                                'Equipment Usage' => 'primary',
                                'Professional Development' => 'gray',
                                default => 'gray',
                            };
                        @endphp
                        <div class="flex justify-start mb-2">
                            <x-filament::badge color="{{ $badgeColor }}">
                                {{ $training->type }}
                            </x-filament::badge>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-4">
                            @php
                                $description = $training->description ?? '';
                                $description = is_string($description) ? $description : '';
                                $cleanDescription = strip_tags($description);
                                $words = str_word_count($cleanDescription, 1);
                                $limitedWords = array_slice($words, 0, 50);
                                $limitedDescription = implode(' ', $limitedWords);
                                if (count($words) > 50) {
                                    $limitedDescription .= '...';
                                }
                            @endphp
                            {{ $limitedDescription }}
                        </p>

                        <!-- Footer with Date and Button -->
                        <div
                            class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $training->created_at->format('M j, Y') }}
                            </span>

                            <x-filament::button size="sm" color="primary"
                                x-on:click="$dispatch('open-modal', { id: 'training-modal-{{ $training->id }}' })"
                                class="group-hover:shadow-md transition-shadow whitespace-nowrap flex-shrink-0">
                                Read More
                            </x-filament::button>
                        </div>
                    </div>
                </div>
                    @endforeach
                </x-filament::grid>

                <!-- Training Modals -->
                @foreach($this->getTrainings() as $training)
                    <x-filament::modal id="training-modal-{{ $training->id }}" width="4xl" slide-over>
                        <x-slot name="heading">
                            {{ $training->title }}
                        </x-slot>

                        @include('filament.staff.training-modal', ['training' => $training])
                    </x-filament::modal>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center py-16 px-4">
                    <x-heroicon-o-academic-cap class="w-20 h-20 text-gray-400 mb-6" />
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 text-center">No training
                        materials found</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                        @if ($selectedType)
                            No training materials available for "{{ $selectedType }}" category.
                        @else
                            No published training materials are currently available.
                        @endif
                    </p>
                </div>
            @endif

            <!-- Pagination -->
            @if ($this->getTrainings()->hasPages())
                <div class="mt-8">
                    {{ $this->getTrainings()->links() }}
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
