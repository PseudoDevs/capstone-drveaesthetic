<div class="space-y-6">
    <!-- Training Header -->
    <div class="text-center space-y-4">
        @if($training->thumbnail)
            <img src="{{ Storage::url($training->thumbnail) }}" 
                 alt="{{ $training->title }}" 
                 class="w-full h-64 object-cover rounded-lg mx-auto">
        @else
            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <x-heroicon-o-academic-cap class="w-16 h-16 text-gray-400" />
            </div>
        @endif
        
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $training->title }}</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                @switch($training->type)
                    @case('Safety Protocol')
                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @break
                    @case('Customer Service')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @break
                    @case('Technical Training')
                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @break
                    @case('Policy Guidelines')
                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @break
                    @case('Equipment Usage')
                        bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                        @break
                    @case('Emergency Procedures')
                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @break
                    @case('Quality Standards')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @break
                    @case('Professional Development')
                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @break
                    @default
                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                @endswitch
            ">
                {{ $training->type }}
            </span>
        </div>
    </div>

    <!-- Training Content -->
    <div class="prose prose-lg dark:prose-invert max-w-none">
        {!! $training->description !!}
    </div>

    <!-- Footer Info -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
            <span>Published: {{ $training->created_at->format('F j, Y') }}</span>
            <span>Last updated: {{ $training->updated_at->format('F j, Y') }}</span>
        </div>
    </div>
</div>