<x-filament-panels::page>
    {{-- Real-time Pusher Integration --}}
    <div wire:ignore 
         x-data="{ 
            channel: null,
            init() {
                this.setupPusher();
            },
            setupPusher() {
                // Leave previous channel if exists
                if (this.channel) {
                    window.Echo.leave(this.channel);
                }
                
                // Only setup if we have a chat and Echo is available
                if ($wire.currentChat && window.Echo) {
                    const chatId = $wire.currentChat.id;
                    this.channel = `chat.${chatId}`;
                    
                    window.Echo.private(this.channel)
                        .listen('.message.sent', (e) => {
                            $wire.$refresh();
                            $nextTick(() => {
                                $dispatch('message-received');
                            });
                        });
                }
            }
         }"
         x-init="init()"
         @chat-selected.window="setupPusher()">
    </div>
    
    {{-- Main Chat Layout - Left and Right Columns --}}
    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-12rem)]">

        {{-- Left Column: Client List --}}
        <div class="lg:w-80 lg:flex-shrink-0 space-y-4">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center space-x-2">
                        <x-filament::icon icon="heroicon-o-users" class="h-5 w-5" />
                        <span>Clients</span>
                    </div>
                </x-slot>

                <x-slot name="headerActions">
                    <div class="relative flex-1 max-w-sm">
                        <x-filament::input wire:model.live.debounce.300ms="searchTerm" type="text"
                            placeholder="Search clients..." prefix-icon="heroicon-o-magnifying-glass" class="w-full" />
                        @if ($searchTerm)
                            <button wire:click="$set('searchTerm', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <x-filament::icon icon="heroicon-o-x-mark" class="h-4 w-4" />
                            </button>
                        @endif
                    </div>
                </x-slot>

                <div class="space-y-3 max-h-[600px] overflow-y-auto">
                    {{-- Debug info --}}
                    @if(config('app.debug'))
                        <div class="text-xs text-gray-500 p-2 bg-gray-100 dark:bg-gray-800">
                            Debug: clients count = {{ $clients->count() }}
                        </div>
                    @endif
                    
                    @if ($clients->count() > 0)
                        @foreach ($clients as $client)
                            <x-filament::section wire:click="selectClient({{ $client->id }})"
                                class="cursor-pointer transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800/50
                                        {{ $selectedClientId === $client->id ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-950' : '' }}">
                                <div class="flex items-center space-x-4 p-2">
                                    <x-filament::avatar 
                                        src="{{ $client->avatar ?? '' }}"
                                        alt="{{ $client->name }}"
                                        size="lg"
                                    >
                                        {{ substr($client->name, 0, 2) }}
                                    </x-filament::avatar>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $client->name }}
                                            </h3>
                                            @if ($selectedClientId === $client->id)
                                                <x-filament::badge color="success" size="sm">
                                                    Active
                                                </x-filament::badge>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $client->email }}
                                        </p>
                                        <div class="flex items-center mt-1 space-x-1">
                                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="text-xs text-green-600 dark:text-green-400">Online</span>
                                        </div>
                                    </div>

                                    @if ($selectedClientId === $client->id)
                                        <x-filament::icon icon="heroicon-o-check-circle"
                                            class="h-5 w-5 text-primary-600" />
                                    @endif
                                </div>
                            </x-filament::section>
                        @endforeach
                    @else
                        <x-filament::section>
                            <div class="text-center py-12">
                                <x-filament::icon icon="heroicon-o-users"
                                    class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No clients found
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400">Try adjusting your search terms or check
                                    back later</p>
                            </div>
                        </x-filament::section>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- Right Column: Chat Messages --}}
        <div class="flex-1 flex flex-col min-h-0 h-full">
            {{-- Debug info --}}
            @if(config('app.debug'))
                <div class="text-xs text-gray-500 p-2 bg-gray-100 dark:bg-gray-800">
                    Debug: selectedClientId = {{ $selectedClientId ?? 'null' }} | 
                    selectedClient = {{ $selectedClient ? $selectedClient->name : 'null' }} |
                    currentChat = {{ $currentChat ? $currentChat->id : 'null' }} |
                    messages count = {{ $messages ? $messages->count() : 'null' }} |
                    condition result = {{ ($selectedClientId && $selectedClient) ? 'TRUE' : 'FALSE' }}
                </div>
            @endif
            
            {{-- Testing with simplified condition --}}
            @if ($selectedClientId)
                <div class="bg-yellow-100 p-4 mb-4">
                    <h2>CHAT SECTION IS VISIBLE!</h2>
                    <p>Selected Client ID: {{ $selectedClientId }}</p>
                    <p>Selected Client: {{ $selectedClient ? $selectedClient->name : 'null' }}</p>
                </div>

                {{-- Messages Area --}}
                <x-filament::section class="flex-1 flex flex-col min-h-0 overflow-hidden">
                    <x-slot name="heading">
                         <div class="flex items-center justify-between w-full">
                            <div class="flex items-center space-x-4">
                                <x-filament::avatar 
                                    src="{{ $selectedClient->avatar ?? '' }}"
                                    alt="{{ $selectedClient->name }}"
                                    size="lg"
                                >
                                    {{ substr($selectedClient->name, 0, 2) }}
                                </x-filament::avatar>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedClient->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $selectedClient->email }}
                                    </p>
                                    <div class="flex items-center mt-1 space-x-1">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-green-600 dark:text-green-400">Active now</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2 text-xs text-green-600 dark:text-green-400">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span>Real-time</span>
                                </div>
                            </div>
                        </div>
                    </x-slot>

                    <div class="flex-1 overflow-y-auto space-y-4 p-4" 
                         x-data="{ 
                            scrollToBottom() { 
                                this.$el.scrollTop = this.$el.scrollHeight;
                            },
                            init() {
                                this.scrollToBottom();
                                this.$watch('$wire.messages', () => {
                                    this.$nextTick(() => this.scrollToBottom());
                                });
                            }
                         }"
                         x-init="init()"
                         @message-sent.window="scrollToBottom()"
                         @message-received.window="scrollToBottom()"

                        @if ($messages->count() > 0)
                            @foreach ($messages as $message)
                                <div
                                    class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%]">
                                        <x-filament::section
                                            class="!p-3 {{ $message->user_id === Auth::id() ? 'bg-primary-50 dark:bg-primary-950' : '' }}">
                                            <div
                                                class="flex items-start space-x-3 {{ $message->user_id === Auth::id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                                <x-filament::avatar 
                                                    src="{{ $message->user->avatar ?? '' }}"
                                                    alt="{{ $message->user->name }}"
                                                    size="sm"
                                                >
                                                    {{ substr($message->user->name, 0, 1) }}
                                                </x-filament::avatar>
                                                
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $message->user->name }}
                                                        </h4>
                                                        <x-filament::badge size="sm" color="gray">
                                                            {{ $message->created_at->format('H:i') }}
                                                        </x-filament::badge>
                                                    </div>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                        {{ $message->message }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $message->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </x-filament::section>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-12">
                                <x-filament::icon icon="heroicon-o-chat-bubble-left-right"
                                    class="mx-auto h-8 w-8 text-gray-400 mb-4" />
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Start the
                                    conversation</h3>
                                <p class="text-gray-500 dark:text-gray-400">Send your first message to
                                    {{ $selectedClient->name }} and begin building a great relationship!</p>
                            </div>
                        @endif
                    </div>
                </x-filament::section>

                {{-- Message Input --}}
                <x-filament::section class="flex-shrink-0">
                    <x-slot name="heading">
                        Send Message to {{ $selectedClient->name }}
                    </x-slot>

                    <form wire:submit="sendMessage" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="relative">
                                <x-filament::input.wrapper>
                                    <textarea wire:model="newMessage" placeholder="Type your message here..." rows="3"
                                        class="block w-full border-0 px-3 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none resize-none"
                                        x-data="{
                                            resize() {
                                                $el.style.height = 'auto';
                                                $el.style.height = Math.min($el.scrollHeight, 120) + 'px';
                                            }
                                        }" x-init="resize()" x-on:input="resize()"
                                        @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); $el.style.height = 'auto'; }"></textarea>
                                </x-filament::input.wrapper>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Press Enter to send • Shift+Enter for new line
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">

                            </div>

                            <x-filament::button type="submit" icon="heroicon-o-paper-airplane" size="lg">
                                Send Message
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            @else
                {{-- No Client Selected --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center space-x-2">
                            <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="h-5 w-5" />
                            <span>Welcome to Client Chat</span>
                        </div>
                    </x-slot>

                    <div class="text-center py-12">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-right"
                            class="mx-auto h-16 w-16 text-gray-400 mb-6" />
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Start a Conversation</h3>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Select a client from
                            the sidebar to start a meaningful conversation and provide excellent support.</p>

                        <div class="grid grid-cols-1 gap-4 max-w-lg mx-auto">
                            <x-filament::section class="text-center">
                                <x-filament::icon icon="heroicon-o-bolt"
                                    class="mx-auto h-8 w-8 text-primary-600 mb-3" />
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Real-time Messaging</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Instant communication with your
                                    clients</p>
                            </x-filament::section>

                            <x-filament::section class="text-center">
                                <x-filament::icon icon="heroicon-o-check-circle"
                                    class="mx-auto h-8 w-8 text-success-600 mb-3" />
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Professional Support</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Provide excellent customer service
                                </p>
                            </x-filament::section>
                        </div>
                    </div>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>
