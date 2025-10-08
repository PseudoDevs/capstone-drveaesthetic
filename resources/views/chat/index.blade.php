<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Telegram-inspired Chat Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        #activeChat {
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
        }
        
        #activeChat.hidden {
            display: none !important;
        }
        
        #messagesContainer {
            flex: 1 1 auto !important;
            overflow-y: auto !important;
            min-height: 0 !important;
            background: #f0f2f5;
            padding: 20px;
        }
        
        /* Telegram-style message bubbles */
        .message {
            margin-bottom: 8px;
            display: flex;
            align-items: flex-end;
            animation: messageSlideIn 0.3s ease-out;
        }
        
        .message.sent {
            justify-content: flex-end;
        }
        
        .message.received {
            justify-content: flex-start;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .message.sent .message-bubble {
            background: #0088cc;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.received .message-bubble {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            border: 1px solid #e5e5e5;
        }
        
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 4px;
            text-align: right;
        }
        
        .message.received .message-time {
            text-align: left;
        }
        
        /* Telegram-style sidebar */
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e5e5e5;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }
        
        .conversation-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }
        
        .conversation-item:hover {
            background: #f5f5f5;
        }
        
        .conversation-item.active {
            background: #e3f2fd;
            border-left: 3px solid #0088cc;
        }
        
        .conversation-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-right: 12px;
            position: relative;
        }
        
        .online-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #4caf50;
            border: 2px solid white;
            border-radius: 50%;
        }
        
        .conversation-info {
            flex: 1;
            min-width: 0;
        }
        
        .conversation-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }
        
        .conversation-preview {
            font-size: 14px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .conversation-time {
            font-size: 12px;
            color: #999;
            margin-top: 2px;
        }
        
        /* Telegram-style input */
        .message-input-container {
            background: white;
            border-top: 1px solid #e5e5e5;
            padding: 16px 20px;
        }
        
        #messageInput {
            background: #f0f2f5;
            border: none;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 15px;
            resize: none;
            outline: none;
            transition: all 0.2s;
        }
        
        #messageInput:focus {
            background: white;
            box-shadow: 0 0 0 2px #0088cc;
        }
        
        #sendButton {
            background: #0088cc;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        #sendButton:hover {
            background: #0077b3;
            transform: scale(1.05);
        }
        
        #sendButton:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        /* Typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background: white;
            border-radius: 18px;
            margin: 4px 0;
            max-width: 80px;
        }
        
        .typing-dots {
            display: flex;
            gap: 4px;
        }
        
        .typing-dot {
            width: 6px;
            height: 6px;
            background: #999;
            border-radius: 50%;
            animation: typingDot 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typingDot {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.5;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }
        
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .w-80 {
                width: 100%;
                position: absolute;
                z-index: 10;
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .w-80.open {
                transform: translateX(0);
            }
            
            .message-bubble {
                max-width: 85%;
            }
            
            /* Mobile chat header with back button */
            #chatHeader {
                padding: 12px 16px;
                position: relative;
            }
            
            .mobile-back-button {
                display: flex;
                align-items: center;
                background: none;
                border: none;
                color: #0088cc;
                font-size: 16px;
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                transition: background-color 0.2s;
            }
            
            .mobile-back-button:hover {
                background: rgba(0, 136, 204, 0.1);
            }
            
            .mobile-back-button i {
                margin-right: 8px;
            }
            
            /* Mobile conversation switcher */
            .mobile-conversation-switcher {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 16px;
                background: white;
                border-bottom: 1px solid #e5e5e5;
                position: sticky;
                top: 0;
                z-index: 5;
            }
            
            .mobile-conversation-info {
                display: flex;
                align-items: center;
                flex: 1;
            }
            
            .mobile-conversation-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                margin-right: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 14px;
            }
            
            .mobile-conversation-details h3 {
                font-size: 16px;
                font-weight: 600;
                margin: 0;
                color: #333;
            }
            
            .mobile-conversation-details p {
                font-size: 12px;
                color: #666;
                margin: 0;
            }
            
            .mobile-switch-button {
                background: #0088cc;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 8px 12px;
                font-size: 12px;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            
            .mobile-switch-button:hover {
                background: #0077b3;
            }
            
            /* Hide desktop elements on mobile */
            .desktop-only {
                display: none;
            }
        }
        
        /* Desktop styles */
        @media (min-width: 769px) {
            .mobile-only {
                display: none;
            }
            
            .mobile-back-button {
                display: none;
            }
            
            .mobile-conversation-switcher {
                display: none;
            }
        }
        
        /* Scrollbar styling */
        #messagesContainer::-webkit-scrollbar {
            width: 6px;
        }
        
        #messagesContainer::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #messagesContainer::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        #messagesContainer::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-80 sidebar flex flex-col">
            <!-- Header -->
            <div class="px-4 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="conversation-avatar bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                            {{ substr($currentUser->name, 0, 2) }}
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">Chats</h1>
                            <p class="text-sm text-gray-500">{{ $currentUser->name }}</p>
                        </div>
                    </div>
                    <a href="{{ 
                        $currentUser->role === 'Client' ? '/users/dashboard' : 
                        ($currentUser->role === 'Admin' ? '/admin' : '/staff') 
                    }}"
                        class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto" id="conversationsList">
                <div class="p-4 text-center text-gray-500">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                    <p class="text-sm">Loading conversations...</p>
                </div>
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
            <div id="activeChat" class="hidden flex-1 flex flex-col relative z-0" style="min-height: 100vh;">

                <!-- Mobile Conversation Switcher -->
                <div class="mobile-conversation-switcher mobile-only">
                    <div class="mobile-conversation-info">
                        <div id="mobileSelectedUserAvatar" class="mobile-conversation-avatar bg-gradient-to-br from-blue-400 to-blue-600">
                        </div>
                        <div class="mobile-conversation-details">
                            <h3 id="mobileSelectedUserName"></h3>
                            <p id="mobileSelectedUserStatus">Online</p>
                        </div>
                    </div>
                    <button class="mobile-switch-button" onclick="toggleConversationList()">
                        <i class="fas fa-list"></i> Switch
                    </button>
                </div>

                <!-- Chat Header -->
                <div id="chatHeader"
                    class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <!-- Mobile Back Button -->
                    <button class="mobile-back-button mobile-only" onclick="toggleConversationList()">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Chats</span>
                    </button>
                    
                    <!-- Desktop Header Content -->
                    <div class="flex items-center space-x-3 desktop-only">
                        <div id="selectedUserAvatar" class="conversation-avatar bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                        </div>
                        <div>
                            <h2 id="selectedUserName" class="text-lg font-semibold text-gray-900"></h2>
                            <p class="text-sm text-green-600 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Online
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div id="typingIndicator" class="typing-indicator hidden">
                            <div class="typing-dots">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="messagesContainer"
                    class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-blue-50 to-white">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Message Input -->
                <div class="message-input-container relative z-10">
                    <form id="messageForm" class="flex items-end space-x-3">
                        <div class="flex-1">
                            <textarea id="messageInput" placeholder="Type a message..." rows="1"
                                class="w-full resize-none focus:ring-2 focus:ring-blue-500 transition-colors max-h-32"
                                maxlength="1000"></textarea>
                        </div>
                        <button type="submit" id="sendButton"
                            class="flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
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
            csrfToken: "{{ csrf_token() }}"
        };
        console.log('🔧 ChatData initialized:', window.ChatData);
        
        // Mobile navigation functions
        function toggleConversationList() {
            const sidebar = document.querySelector('.w-80');
            if (sidebar) {
                sidebar.classList.toggle('open');
            }
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.w-80');
            const chatArea = document.querySelector('#chatArea');
            
            if (window.innerWidth <= 768 && sidebar && chatArea) {
                if (!sidebar.contains(event.target) && !event.target.closest('.mobile-back-button') && !event.target.closest('.mobile-switch-button')) {
                    sidebar.classList.remove('open');
                }
            }
        });
        
        // Update mobile conversation switcher when selecting a conversation
        function updateMobileConversationSwitcher(userName, userAvatar) {
            const mobileName = document.getElementById('mobileSelectedUserName');
            const mobileAvatar = document.getElementById('mobileSelectedUserAvatar');
            
            if (mobileName) {
                mobileName.textContent = userName;
            }
            
            if (mobileAvatar) {
                mobileAvatar.textContent = userName.substring(0, 2).toUpperCase();
            }
        }
        
        // Close sidebar after selecting a conversation on mobile
        function selectConversationMobile(conversationId) {
            // Close the sidebar
            const sidebar = document.querySelector('.w-80');
            if (sidebar && window.innerWidth <= 768) {
                sidebar.classList.remove('open');
            }
            
            // Continue with normal conversation selection
            if (typeof selectConversation === 'function') {
                selectConversation(conversationId);
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.w-80');
            if (window.innerWidth > 768 && sidebar) {
                sidebar.classList.remove('open');
            }
        });
    </script>

    @vite('resources/js/chat.js')
</body>

</html>
