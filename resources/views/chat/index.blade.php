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
    <!-- Main Chat Container -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar - User List -->
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
                    <a href="/" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Search (only show for non-clients since clients only need to see the staff) -->
            @if($currentUser->role !== 'Client')
            <div class="p-4">
                <div class="relative">
                    <input type="text" placeholder="Search users..." id="searchUsers"
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

            <!-- User List -->
            <div class="flex-1 overflow-y-auto">
                @if($currentUser->role === 'Client')
                    <!-- For clients, directly show the staff member -->
                    @if($staffMember)
                        <div class="user-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors"
                            data-user-id="{{ $staffMember->id }}" data-user-name="{{ $staffMember->name }}">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($staffMember->name, 0, 2) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $staffMember->name }}</h3>
                                        <span class="text-xs text-green-600">● Online</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $staffMember->email }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {{ $staffMember->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="px-4 py-6 text-center text-gray-500">
                            <p class="text-sm">No staff available</p>
                        </div>
                    @endif
                @else
                    <!-- For staff/admin, show the full user list -->
                    @foreach ($users as $user)
                        <div class="user-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors"
                            data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</h3>
                                        <span class="text-xs text-gray-500">2m</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-gray-50 h-full" id="chatArea">

            <!-- Default state - No chat selected -->
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
                    <p class="text-gray-500">Choose a person from the sidebar to start chatting</p>
                </div>
            </div>

            <!-- Active Chat Container -->
            <div id="activeChatContainer" class="flex-1 flex-col hidden h-full">

                <!-- Chat Header -->
                <div id="chatHeader"
                    class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div id="selectedUserAvatar"
                            class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                        </div>
                        <div>
                            <h2 id="selectedUserName" class="text-lg font-semibold text-gray-900"></h2>
                            <p class="text-sm text-green-600">● Online</p>
                        </div>
                    </div>
                    
                </div>

                <!-- Messages Area -->
                <div id="messagesContainer"
                    class="flex-1 overflow-y-auto p-4 space-y-4 bg-gradient-to-b from-blue-50 to-white min-h-0">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Message Input -->
                <div class="bg-white px-4 py-4 border-t border-gray-200 flex-shrink-0">
                    <form id="messageForm" class="flex items-end space-x-3">
                        <div class="flex-1 relative">
                            <textarea id="messageInput" placeholder="Type a message..." rows="1"
                                class="w-full px-4 py-3 bg-gray-100 border-0 rounded-full resize-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors max-h-32"></textarea>
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

    <!-- JavaScript -->
    <script>
        // Global variables
        let currentUserId = {{ $currentUser->id }};
        let selectedUserId = null;
        let currentChatId = null;
        let pusherChannel = null;
        let searchTimeout = null;
        let isSearching = false;

        // Initialize app
        document.addEventListener('DOMContentLoaded', function() {
            initializeChat();
            setupEventListeners();
            setupPusher();
            loadUserConversations();
            
        });

        function initializeChat() {
            // Auto-resize textarea
            const messageInput = document.getElementById('messageInput');
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 128) + 'px';
            });
        }


        function setupEventListeners() {
            // User selection
            document.querySelectorAll('.user-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectUser(this.dataset.userId, this.dataset.userName);
                });
            });

            // Message form submission
            document.getElementById('messageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });

            // Enter key to send message
            document.getElementById('messageInput').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            // Search functionality with debouncing (only for non-clients)
            const searchInput = document.getElementById('searchUsers');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.trim();

                    // Clear previous timeout
                    if (searchTimeout) {
                        clearTimeout(searchTimeout);
                    }

                    // If query is empty, hide search results and show conversations
                    if (query === '') {
                        hideSearchResults();
                        showConversations();
                        return;
                    }

                    // Debounce search
                    searchTimeout = setTimeout(() => {
                        searchStaff(query);
                    }, 300);
                });

                // Click outside to hide search results
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#searchUsers') && !e.target.closest('#searchResults')) {
                        hideSearchResults();
                    }
                });
            }
        }

        function setupPusher() {
            // Laravel Echo is initialized in bootstrap.js as window.Echo
            console.log('Laravel Echo initialized:', window.Echo);

            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                // Add connection state logging
                window.Echo.connector.pusher.connection.bind('connected', () => {
                    console.log('Pusher connected successfully via Echo');
                });

                window.Echo.connector.pusher.connection.bind('disconnected', () => {
                    console.log('Pusher disconnected');
                });

                window.Echo.connector.pusher.connection.bind('error', (error) => {
                    console.error('Pusher connection error:', error);
                });
            }
        }

        function selectUser(userId, userName) {
            selectedUserId = parseInt(userId);

            console.log('Selecting user:', {
                userId,
                userName,
                selectedUserId
            });

            // Update UI - remove selection from all existing items
            document.querySelectorAll('.user-item').forEach(item => {
                item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
            });

            // Add selection to current user if it exists in the list
            const selectedUserElement = document.querySelector(`[data-user-id="${userId}"]`);
            if (selectedUserElement) {
                selectedUserElement.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
            }

            // Show chat area
            document.getElementById('noChatSelected').classList.add('hidden');
            document.getElementById('activeChatContainer').classList.remove('hidden');
            document.getElementById('activeChatContainer').classList.add('flex');

            // Update header
            document.getElementById('selectedUserName').textContent = userName;
            document.getElementById('selectedUserAvatar').textContent = userName.substring(0, 2);

            // Clear previous messages
            document.getElementById('messagesContainer').innerHTML = '';

            // Show loading state
            document.getElementById('messagesContainer').innerHTML = `
                <div class="flex justify-center items-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500">Loading messages...</span>
                </div>
            `;

            // Load messages
            loadMessages(userId);
        }

        async function loadUserConversations() {
            // Skip loading conversations for clients since they auto-connect to staff
            if ({{ $currentUser->role === 'Client' ? 'true' : 'false' }}) {
                return;
            }

            try {
                console.log('Loading user conversations...');

                const response = await fetch('/chat/conversations', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Conversations loaded:', data);
                    updateConversationsList(data.conversations);
                } else {
                    console.error('Failed to load conversations:', response.status);
                    // Fallback to server-side rendered data
                    const existingUsers = @json($users ?? []);
                    updateUserList(existingUsers);
                }

            } catch (error) {
                console.error('Error loading conversations:', error);
                // Fallback to server-side rendered data
                const existingUsers = @json($users ?? []);
                updateUserList(existingUsers);
            }
        }

        function updateConversationsList(conversations) {
            const userListContainer = document.querySelector('.flex-1.overflow-y-auto');
            userListContainer.innerHTML = '';

            if (conversations.length === 0) {
                userListContainer.innerHTML = `
                    <div class="p-4 text-center text-gray-500">
                        <p class="text-sm">No conversations yet</p>
                        <p class="text-xs mt-1">Search for clinic staff to start chatting</p>
                    </div>
                `;
                return;
            }

            conversations.forEach(conversation => {
                const conversationItem = createConversationItem(conversation);
                userListContainer.appendChild(conversationItem);
            });

            // After updating the list, restore the selection if we have a selected user
            if (selectedUserId) {
                updateSelectedUserInSidebar();
            }
        }

        // Fallback function for backwards compatibility
        function updateUserList(users) {
            // Convert users to conversation format for backwards compatibility
            const conversations = users.map(user => ({
                user: user,
                chat_id: null,
                last_message_at: null,
                latest_message: null
            }));
            updateConversationsList(conversations);
        }

        function createUserItem(user) {
            const userDiv = document.createElement('div');
            userDiv.className =
                'user-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors';
            userDiv.dataset.userId = user.id;
            userDiv.dataset.userName = user.name;

            userDiv.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            ${user.name.substring(0, 2)}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900 truncate">${user.name}</h3>
                            <span class="text-xs text-gray-500">2m</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate">${user.email}</p>
                        <div class="flex items-center mt-1">
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                ${user.role}
                            </span>
                        </div>
                    </div>
                </div>
            `;

            userDiv.addEventListener('click', function() {
                selectUser(user.id, user.name);
            });

            return userDiv;
        }

        function createConversationItem(conversation) {
            const user = conversation.user;
            const latestMessage = conversation.latest_message;

            const conversationDiv = document.createElement('div');
            conversationDiv.className = 'user-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors';
            conversationDiv.dataset.userId = user.id;
            conversationDiv.dataset.userName = user.name;
            conversationDiv.dataset.chatId = conversation.chat_id || '';

            // Format timestamp
            let timeDisplay = '';
            if (conversation.last_message_at) {
                const messageTime = new Date(conversation.last_message_at);
                const now = new Date();
                const diffMs = now - messageTime;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) timeDisplay = 'now';
                else if (diffMins < 60) timeDisplay = `${diffMins}m`;
                else if (diffHours < 24) timeDisplay = `${diffHours}h`;
                else timeDisplay = `${diffDays}d`;
            }

            // Format last message preview
            let messagePreview = '';
            let messageClass = 'text-gray-500';

            if (latestMessage) {
                const prefix = latestMessage.is_own_message ? 'You: ' : '';
                let message = latestMessage.message;

                // Truncate long messages
                if (message.length > 40) {
                    message = message.substring(0, 37) + '...';
                }

                messagePreview = `${prefix}${message}`;
                messageClass = latestMessage.is_own_message ? 'text-blue-600' : 'text-gray-700';
            } else {
                messagePreview = 'Click to start conversation';
                messageClass = 'text-gray-400 italic';
            }

            conversationDiv.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            ${user.name.substring(0, 2)}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900 truncate">${user.name}</h3>
                            ${timeDisplay ? `<span class="text-xs text-gray-500">${timeDisplay}</span>` : ''}
                        </div>
                        <p class="text-sm ${messageClass} truncate">${messagePreview}</p>
                        <div class="flex items-center mt-1">
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                ${user.role}
                            </span>
                        </div>
                    </div>
                </div>
            `;

            conversationDiv.addEventListener('click', function() {
                selectUser(user.id, user.name);
            });

            return conversationDiv;
        }

        async function searchStaff(query) {
            if (isSearching) return;
            isSearching = true;

            try {
                // For now, let's use the API endpoint but with session authentication
                const response = await fetch(`/api/client/chats/search/staff?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin' // Include cookies for session auth
                });

                if (response.ok) {
                    const data = await response.json();
                    showSearchResults(data.data);
                } else {
                    console.error('Search failed:', response.status);
                    showSearchResults([]);
                }
            } catch (error) {
                console.error('Error searching staff:', error);
                showSearchResults([]);
            } finally {
                isSearching = false;
            }
        }

        function showSearchResults(staff) {
            const searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = '';

            if (staff.length === 0) {
                searchResults.innerHTML = `
                    <div class="p-3 text-center text-gray-500">
                        <p class="text-sm">No clinic staff found</p>
                    </div>
                `;
            } else {
                staff.forEach(person => {
                    const resultItem = document.createElement('div');
                    resultItem.className =
                        'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0';

                    resultItem.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                ${person.name.substring(0, 2)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate">${person.name}</h3>
                                <p class="text-xs text-gray-500 truncate">${person.email}</p>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                    ${person.role}
                                </span>
                            </div>
                        </div>
                    `;

                    resultItem.addEventListener('click', function() {
                        selectUser(person.id, person.name);
                        hideSearchResults();
                        document.getElementById('searchUsers').value = '';
                    });

                    searchResults.appendChild(resultItem);
                });
            }

            searchResults.classList.remove('hidden');
        }

        function hideSearchResults() {
            const searchResults = document.getElementById('searchResults');
            if (searchResults) {
                searchResults.classList.add('hidden');
            }
        }

        function showConversations() {
            loadUserConversations();
        }

        async function loadMessages(userId) {
            try {
                console.log('Loading messages for user:', userId);

                const response = await fetch(`/chat/messages/${userId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                console.log('Response status:', response.status);

                if (response.ok) {
                    const data = await response.json();
                    console.log('Messages data:', data);

                    // The web route returns { chat_id, messages }
                    currentChatId = data.chat_id;
                    displayMessages(data.messages || []);

                    // Setup real-time listening for this chat
                    setupChatChannel();
                } else {
                    const errorData = await response.text();
                    console.error('Failed to load messages:', response.status, errorData);
                    showEmptyConversation();
                }

            } catch (error) {
                console.error('Error loading messages:', error);
                showEmptyConversation();
            }
        }

        function showEmptyConversation() {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = `
                <div class="flex justify-center items-center h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.454L3 21l2.454-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-2">No messages yet</p>
                        <p class="text-sm text-gray-400">Start the conversation by sending a message</p>
                    </div>
                </div>
            `;
        }

        function setupChatChannel() {
            if (window.Echo && currentChatId) {
                // Leave previous channel if exists
                if (pusherChannel) {
                    window.Echo.leave(`chat.${pusherChannel}`);
                }

                // Subscribe to new chat channel using Laravel Echo
                console.log(`Subscribing to private chat channel: chat.${currentChatId}`);
                
                pusherChannel = currentChatId;
                window.Echo.private(`chat.${currentChatId}`)
                    .listen('MessageSent', (e) => {
                        console.log('Received message via Echo:', e);
                        if (e.message.user_id !== currentUserId) {
                            addMessageToUI(e.message);

                            // Immediately refresh conversations list to update last message and order
                            setTimeout(() => {
                                if ({{ $currentUser->role === 'Client' ? 'false' : 'true' }}) {
                                    loadUserConversations();
                                    updateSelectedUserInSidebar();
                                }
                            }, 100); // Very fast refresh for real-time updates
                        }
                    })
                    .error((error) => {
                        console.error('Echo subscription error:', error);
                    });
            } else if (!window.Echo) {
                console.error('Laravel Echo not initialized');
            } else if (!currentChatId) {
                console.error('No current chat ID for subscription');
            }
        }

        function displayMessages(messages) {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = '';

            messages.forEach(message => {
                addMessageToUI(message);
            });

            scrollToBottom();
        }

        function addMessageToUI(message) {
            const container = document.getElementById('messagesContainer');
            const isOwnMessage = message.user_id === currentUserId;

            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;

            const messageTime = new Date(message.created_at).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md">
                    <div class="flex items-end space-x-2 ${isOwnMessage ? 'flex-row-reverse space-x-reverse' : ''}">
                        ${!isOwnMessage ? `
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                ${message.user.name.substring(0, 2)}
                            </div>
                            ` : ''}
                        <div class="${isOwnMessage ? 'bg-blue-500 text-white' : 'bg-white text-gray-900'} px-4 py-2 rounded-2xl shadow-sm">
                            <p class="text-sm">${message.message}</p>
                            <p class="text-xs ${isOwnMessage ? 'text-blue-100' : 'text-gray-500'} mt-1">${messageTime}</p>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(messageDiv);
            scrollToBottom();
        }

        async function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();

            if (!message || !selectedUserId) return;

            // Disable send button temporarily
            const sendButton = document.getElementById('sendButton');
            sendButton.disabled = true;

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message,
                        receiver_id: selectedUserId
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.status === 'success') {
                        addMessageToUI(data.message);
                        messageInput.value = '';
                        messageInput.style.height = 'auto';

                        // Update the current chat ID if this was the first message
                        const wasNewConversation = !currentChatId;
                        if (!currentChatId && data.message.chat_id) {
                            currentChatId = data.message.chat_id;
                            setupChatChannel();
                        }

                        // Always refresh sidebar to show latest message and timestamp
                        console.log('Updating sidebar with latest message...');
                        setTimeout(() => {
                            loadUserConversations();
                            updateSelectedUserInSidebar();
                        }, wasNewConversation ? 500 : 100); // Small delay for new conversations, immediate for existing
                    } else {
                        console.error('Send message failed:', data.message);
                        showNotification('Failed to send message', 'error');
                    }
                } else {
                    const errorData = await response.text();
                    console.error('Failed to send message:', response.status, errorData);
                    showNotification('Failed to send message', 'error');
                }

            } catch (error) {
                console.error('Error sending message:', error);
                showNotification('Error sending message', 'error');
            } finally {
                sendButton.disabled = false;
            }
        }

        function showNotification(message, type = 'info') {
            // Simple notification function - you can enhance this
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
                type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }

        // Enhanced user experience functions
        function addTypingIndicator(userId) {
            const container = document.getElementById('messagesContainer');
            const existingIndicator = document.getElementById('typing-indicator');

            if (!existingIndicator) {
                const typingDiv = document.createElement('div');
                typingDiv.id = 'typing-indicator';
                typingDiv.className = 'flex justify-start mb-4';
                typingDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        <div class="bg-gray-200 px-4 py-2 rounded-2xl">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(typingDiv);
                scrollToBottom();
            }
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) {
                indicator.remove();
            }
        }

        // Update selected user highlighting in sidebar after refresh
        function updateSelectedUserInSidebar() {
            if (selectedUserId) {
                // Remove previous selections
                document.querySelectorAll('.user-item').forEach(item => {
                    item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
                });

                // Add selection to current user if it exists in the refreshed list
                const selectedUserElement = document.querySelector(`[data-user-id="${selectedUserId}"]`);
                if (selectedUserElement) {
                    selectedUserElement.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
                }
            }
        }

        // Auto-refresh conversations periodically (skip for clients)
        if ({{ $currentUser->role === 'Client' ? 'false' : 'true' }}) {
            setInterval(() => {
                const searchInput = document.getElementById('searchUsers');
                if (!isSearching && (!searchInput || searchInput.value === '')) {
                    loadUserConversations().then(() => {
                        updateSelectedUserInSidebar();
                    });
                }
            }, 10000); // Refresh every 10 seconds for real-time feel
        }
    </script>
</body>

</html>
