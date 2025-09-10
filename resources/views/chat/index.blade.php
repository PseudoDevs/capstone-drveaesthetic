<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-80 bg-white border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr($currentUser->name, 0, 2) }}
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">Chats</h1>
                            <p class="text-sm text-gray-500">{{ $currentUser->name }}</p>
                        </div>
                    </div>
                    <a href="{{ $currentUser->role === 'Client' ? '/users/dashboard' : '/' }}"
                        class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Search (only for non-clients) -->
            @if ($currentUser->role !== 'Client')
                <div class="p-4">
                    <div class="relative">
                        <input type="text" placeholder="Search staff..." id="searchInput"
                            class="w-full pl-10 pr-4 py-2 bg-gray-100 border-0 rounded-full focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                        <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div id="searchResults"
                            class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto z-50 hidden">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto" id="conversationsList">
                @if ($currentUser->role === 'Client' && $staffMember)
                    <!-- Client sees only staff -->
                    <div class="conversation-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors"
                        data-user-id="{{ $staffMember->id }}" data-user-name="{{ $staffMember->name }}">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($staffMember->name, 0, 2) }}
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $staffMember->name }}</h3>
                                    <span class="text-xs text-green-600">● Online</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate">{{ $staffMember->email }}</p>
                                <div class="flex items-center mt-1">
                                    <span
                                        class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $staffMember->role }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Staff/Admin see conversations -->
                    <div class="p-4 text-center text-gray-500">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                        <p class="text-sm">Loading conversations...</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-gray-50" id="chatArea">

            <!-- No Chat Selected -->
            <div id="noChatSelected" class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-24 h-24 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.454L3 21l2.454-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Select a conversation</h2>
                    <p class="text-gray-500">Choose a person to start chatting</p>
                </div>
            </div>

            <!-- Active Chat -->
            <div id="activeChat" class="hidden flex-1 flex flex-col">

                <!-- Chat Header -->
                <div id="chatHeader"
                    class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="selectedUserAvatar"
                            class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                        </div>
                        <div>
                            <h2 id="selectedUserName" class="text-lg font-semibold text-gray-900"></h2>
                            <p class="text-sm text-green-600">● Online</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div id="typingIndicator" class="text-sm text-gray-500 italic hidden">
                            <span>Typing...</span>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="messagesContainer"
                    class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-blue-50 to-white">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Message Input -->
                <div class="bg-white px-4 py-4 border-t border-gray-200">
                    <form id="messageForm" class="flex items-end space-x-3">
                        <div class="flex-1">
                            <textarea id="messageInput" placeholder="Type a message..." rows="1"
                                class="w-full px-4 py-3 bg-gray-100 border-0 rounded-full resize-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors max-h-32"
                                maxlength="1000"></textarea>
                        </div>
                        <button type="submit" id="sendButton"
                            class="p-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pass data to JavaScript -->
    <script>
        window.ChatData = {
            currentUser: @json($currentUser),
            isClient: @json($currentUser->role === 'Client'),
            pusherKey: "{{ config('broadcasting.connections.pusher.key') }}",
            pusherCluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            csrfToken: "{{ csrf_token() }}"
        };
        console.log('🔧 ChatData initialized:', window.ChatData);
    </script>

    @vite('resources/js/chat.js')
</body>

</html>
