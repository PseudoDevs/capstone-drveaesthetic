/**
 * Real-time Chat Module for Laravel 12 + Server-Sent Events
 * Clean implementation with proper real-time messaging
 */

class ChatManager {
    constructor() {
        console.log('🔧 ChatManager constructor called');
        console.log('🔧 window.ChatData:', window.ChatData);
        
        if (!window.ChatData) {
            console.error('❌ window.ChatData is not defined!');
            return;
        }
        
        this.currentUser = window.ChatData.currentUser;
        this.isClient = window.ChatData.isClient;
        this.selectedUserId = null;
        this.selectedUserName = null;
        this.currentChatId = null;
        this.eventSource = null;
        this.displayedMessageIds = new Set();
        this.searchTimeout = null;
        this.lastMessageId = 0;
        this.hasManualSelection = false; // Track if staff manually selected a conversation
        
        console.log('🔧 ChatManager properties set:', {
            currentUser: this.currentUser,
            isClient: this.isClient
        });
        
        this.init();
    }

    async init() {
        console.log('🚀 Chat Manager initializing...', {
            user: this.currentUser,
            isClient: this.isClient
        });

        this.setupEventListeners();
        this.setupEventSource();
        await this.loadConversations();
        
        console.log('✅ Chat Manager initialized');
    }

    setupEventListeners() {
        // Message form submission
        document.getElementById('messageForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });

        // Enter key to send (Shift+Enter for new line)
        document.getElementById('messageInput')?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Auto-resize textarea
        document.getElementById('messageInput')?.addEventListener('input', (e) => {
            e.target.style.height = 'auto';
            e.target.style.height = Math.min(e.target.scrollHeight, 128) + 'px';
        });

        // No search functionality needed - only one staff member

        // Conversation clicks
        document.addEventListener('click', (e) => {
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                const userId = conversationItem.dataset.userId;
                const userName = conversationItem.dataset.userName;
                this.selectConversation(userId, userName);
            }
        });
    }

    setupEventSource(forSpecificUser = null) {
        console.log('🔄 Setting up Server-Sent Events connection...');
        
        // Close existing connection if any
        if (this.eventSource) {
            this.eventSource.close();
        }
        
        // Determine which user ID to use for SSE connection
        let sseUserId;
        if (forSpecificUser) {
            // Staff connecting to specific client chat
            sseUserId = forSpecificUser;
            console.log(`🔄 Staff connecting to client ${forSpecificUser} chat`);
        } else if (this.isClient) {
            // Client connecting to staff chat
            sseUserId = this.currentUser.id;
            console.log(`🔄 Client ${this.currentUser.id} connecting to staff chat`);
        } else {
            // Staff initial connection - will be updated when they select a conversation
            sseUserId = this.currentUser.id;
            console.log(`🔄 Staff initial connection`);
        }
        
        // Set up EventSource connection
        const url = `/chat/stream?user_id=${sseUserId}&last_message_id=${this.lastMessageId || 0}`;
        console.log(`🔗 SSE URL: ${url}`);
        this.eventSource = new EventSource(url);
        
        this.eventSource.onopen = () => {
            console.log('✅ SSE connected');
        };
        
        this.eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            console.log('📨 SSE message received:', data);
            
            switch (data.type) {
                case 'connected':
                    console.log('✅ SSE connection established for chat:', data.chat_id);
                    break;
                case 'message':
                    this.handleIncomingMessage(data.message);
                    this.lastMessageId = Math.max(this.lastMessageId, data.message.id);
                    break;
                case 'heartbeat':
                    console.log('💓 SSE heartbeat');
                    break;
            }
        };
        
        this.eventSource.onerror = (error) => {
            console.error('❌ SSE connection error:', error);
            // Attempt to reconnect after 5 seconds
            setTimeout(() => {
                if (this.eventSource.readyState === EventSource.CLOSED) {
                    console.log('🔄 Attempting to reconnect SSE...');
                    this.setupEventSource(forSpecificUser);
                }
            }, 5000);
        };
    }

    reconnectSSEForUser(userId) {
        console.log(`🔄 Safely reconnecting SSE for user ${userId}...`);
        
        // Close existing connection
        if (this.eventSource) {
            this.eventSource.close();
        }
        
        // Set up new connection for the specific user
        const url = `/chat/stream?user_id=${userId}&last_message_id=${this.lastMessageId || 0}`;
        console.log(`🔗 SSE URL: ${url}`);
        this.eventSource = new EventSource(url);
        
        this.eventSource.onopen = () => {
            console.log(`✅ SSE reconnected for user ${userId}`);
        };
        
        this.eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            console.log('📨 SSE message received:', data);
            
            switch (data.type) {
                case 'connected':
                    console.log('✅ SSE connection established for chat:', data.chat_id);
                    break;
                case 'message':
                    this.handleIncomingMessage(data.message);
                    this.lastMessageId = Math.max(this.lastMessageId, data.message.id);
                    break;
                case 'heartbeat':
                    console.log('💓 SSE heartbeat');
                    break;
            }
        };
        
        this.eventSource.onerror = (error) => {
            console.error(`❌ SSE connection error for user ${userId}:`, error);
            // Attempt to reconnect after 5 seconds
            setTimeout(() => {
                if (this.eventSource.readyState === EventSource.CLOSED) {
                    console.log(`🔄 Attempting to reconnect SSE for user ${userId}...`);
                    this.reconnectSSEForUser(userId);
                }
            }, 5000);
        };
    }

    async loadConversations() {
        try {
            console.log('📨 Loading conversations...');
            
            const response = await fetch('/api/client/chats', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    this.renderConversations(data.data);
                } else {
                    this.showEmptyConversations();
                }
            } else {
                console.error('❌ Failed to load conversations:', response.status);
                this.showEmptyConversations();
            }
        } catch (error) {
            console.error('❌ Error loading conversations:', error);
            this.showEmptyConversations();
        }
    }

    renderConversations(data) {
        const container = document.getElementById('conversationsList');
        
        if (!data || (!data.chats && !data.chat_users)) {
            this.showEmptyConversations();
            return;
        }

        container.innerHTML = '';
        
        // If current user is a client, show only the staff member
        if (this.isClient) {
            if (data.staff) {
                // Find existing chat with staff if any
                let existingChat = null;
                if (data.chats) {
                    existingChat = data.chats.find(chat => 
                        chat.staff_id === data.staff.id && chat.client_id === this.currentUser.id
                    );
                }
                
                const conversation = {
                    user: data.staff,
                    chat_id: existingChat ? existingChat.id : null,
                    last_message_at: existingChat ? existingChat.last_message_at : null,
                    latest_message: existingChat && existingChat.latest_message ? {
                        id: existingChat.latest_message.id,
                        message: existingChat.latest_message.message,
                        user_id: existingChat.latest_message.user_id,
                        is_own_message: existingChat.latest_message.user_id === this.currentUser.id
                    } : null,
                    has_messages: existingChat && existingChat.latest_message ? true : false
                };
                
                const element = this.createConversationElement(conversation);
                container.appendChild(element);
                
                console.log('✅ Rendered staff member for client view');
                
                // Auto-select the staff conversation for clients (only if no manual selection)
                if (!this.hasManualSelection) {
                    setTimeout(() => {
                        console.log('🔄 Auto-selecting staff conversation for client:', {
                            staffId: data.staff.id,
                            staffName: data.staff.name,
                            currentUserId: this.currentUser.id,
                            isClient: this.isClient
                        });
                        // For clients, we want to load messages between client and staff
                        // The API getMessages($userId) finds chat between staff and $userId
                        // So for client to see staff messages, we pass the client's own ID
                        this.selectConversation(this.currentUser.id, data.staff.name, true);
                    }, 100);
                }
            }
        } else {
            // If current user is staff, show all clients (original behavior)
            if (data.chat_users) {
                data.chat_users.forEach(chatUser => {
                    const user = chatUser.user;
                    const chat = chatUser.chat;
                    
                    const conversation = {
                        user: user,
                        chat_id: chat ? chat.id : null,
                        last_message_at: chat ? chat.last_message_at : null,
                        latest_message: chat && chat.latest_message ? {
                            id: chat.latest_message.id,
                            message: chat.latest_message.message,
                            user_id: chat.latest_message.user_id,
                            is_own_message: chat.latest_message.user_id === this.currentUser.id
                        } : null,
                        has_messages: chatUser.has_messages
                    };
                    
                    const element = this.createConversationElement(conversation);
                    container.appendChild(element);
                });
                
                console.log(`✅ Rendered ${data.chat_users.length} chat users`);
                
                // Auto-select the client with the most recent message for staff (only if no manual selection)
                if (!this.hasManualSelection) {
                    const clientWithRecentMessage = data.chat_users.find(chatUser => 
                        chatUser.has_messages && chatUser.chat && chatUser.chat.latest_message
                    );
                    
                    if (clientWithRecentMessage) {
                        setTimeout(() => {
                            console.log('🔄 Auto-selecting client with recent message for staff:', {
                                clientId: clientWithRecentMessage.user.id,
                                clientName: clientWithRecentMessage.user.name,
                                currentUserId: this.currentUser.id,
                                isClient: this.isClient
                            });
                            this.selectConversation(clientWithRecentMessage.user.id, clientWithRecentMessage.user.name, true);
                        }, 100);
                    }
                } else {
                    console.log('🚫 Skipping auto-selection - staff has manual selection');
                }
            }
        }
    }

    createConversationElement(conversation) {
        const user = conversation.user;
        const latestMessage = conversation.latest_message;
        
        const div = document.createElement('div');
        div.className = 'conversation-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors';
        div.dataset.userId = user.id;
        div.dataset.userName = user.name;
        div.dataset.chatId = conversation.chat_id || '';

        // Format time
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

        // Format message preview
        let messagePreview = 'Click to start conversation';
        let messageClass = 'text-gray-400 italic';

        if (latestMessage) {
            const prefix = latestMessage.is_own_message ? 'You: ' : '';
            let message = latestMessage.message;
            if (message.length > 40) {
                message = message.substring(0, 37) + '...';
            }
            messagePreview = `${prefix}${message}`;
            messageClass = latestMessage.is_own_message ? 'text-blue-600' : 'text-gray-700';
        }

        div.innerHTML = `
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
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">${user.role}</span>
                    </div>
                </div>
            </div>
        `;

        return div;
    }

    showEmptyConversations() {
        const container = document.getElementById('conversationsList');
        container.innerHTML = `
            <div class="p-4 text-center text-gray-500">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.454L3 21l2.454-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                    </svg>
                </div>
                <p class="text-sm mb-1">No conversations yet</p>
                <p class="text-xs">Search for users to start chatting</p>
            </div>
        `;
    }

    async selectConversation(userId, userName, isAutoSelect = false) {
        console.log(`💬 Selecting conversation with ${userName} (${userId}) - ${isAutoSelect ? 'auto' : 'manual'}`);
        
        this.selectedUserId = parseInt(userId);
        this.selectedUserName = userName;
        
        // Track manual selections to prevent auto-selection override
        if (!isAutoSelect) {
            this.hasManualSelection = true;
            console.log('🎯 Manual selection detected, preventing auto-selection override');
        }

        // Update UI selection
        this.updateConversationSelection();
        
        // Show chat area
        this.showChatArea();
        
        // Load messages
        await this.loadMessages();
        
        // Reconnect SSE for the selected conversation (important for staff)
        if (!this.isClient) {
            console.log(`🔄 Staff selected client ${userId}, reconnecting SSE...`);
            this.reconnectSSEForUser(parseInt(userId));
        }
    }

    updateConversationSelection() {
        // Remove previous selections
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
        });

        // Add selection to current conversation
        const selectedElement = document.querySelector(`[data-user-id="${this.selectedUserId}"]`);
        if (selectedElement) {
            selectedElement.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
        }
    }

    showChatArea() {
        console.log('🔍 Showing chat area for:', this.selectedUserName);
        document.getElementById('noChatSelected').classList.add('hidden');
        document.getElementById('activeChat').classList.remove('hidden');

        // Update header
        document.getElementById('selectedUserName').textContent = this.selectedUserName;
        document.getElementById('selectedUserAvatar').textContent = this.selectedUserName.substring(0, 2);
        
        console.log('🔍 Chat area shown, activeChat classes:', document.getElementById('activeChat').className);
    }

    async loadMessages() {
        try {
            console.log(`📨 Loading messages for user ${this.selectedUserId}...`);
            
            // Show loading state
            const container = document.getElementById('messagesContainer');
            container.innerHTML = `
                <div class="flex justify-center items-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500">Loading messages...</span>
                </div>
            `;

            const response = await fetch(`/api/client/chats/messages/${this.selectedUserId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                console.log('📨 Messages API response:', data);
                if (data.success && data.data) {
                    this.currentChatId = data.data.chat_id;
                    console.log(`🔄 About to display ${data.data.messages?.length || 0} messages`);
                    this.displayMessages(data.data.messages || []);
                    console.log(`✅ Loaded ${data.data.messages?.length || 0} messages for chat ${this.currentChatId}`);
                } else {
                    console.log('❌ API response indicates no data:', data);
                    this.showEmptyMessages();
                }
            } else {
                console.error('❌ Failed to load messages:', response.status);
                this.showEmptyMessages();
            }
        } catch (error) {
            console.error('❌ Error loading messages:', error);
            this.showEmptyMessages();
        }
    }

    displayMessages(messages) {
        const container = document.getElementById('messagesContainer');
        
        if (!container) {
            console.error('❌ Messages container not found in displayMessages!');
            return;
        }
        
        console.log('🔍 displayMessages called with:', messages);
        console.log('🔍 Container element:', container);
        
        container.innerHTML = '';
        this.displayedMessageIds.clear();

        if (!messages || messages.length === 0) {
            console.log('📭 No messages to display, showing empty state');
            this.showEmptyMessages();
            return;
        }

        console.log(`✅ Displaying ${messages.length} messages`);
        
        messages.forEach((message, index) => {
            console.log(`🔄 Processing message ${index + 1}:`, message);
            this.displayedMessageIds.add(message.id);
            this.addMessageToUI(message, false); // Don't scroll for initial load
        });
        
        console.log('🔍 Container after adding messages:', container.innerHTML.length > 0 ? 'Has content' : 'Empty');

        // Force scroll after all messages are added
        setTimeout(() => this.scrollToBottom(), 100);
    }

    showEmptyMessages() {
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


    handleIncomingMessage(message) {
        // Prevent duplicate messages
        if (this.displayedMessageIds.has(message.id)) {
            console.log('⚠️ Duplicate message ignored:', message.id);
            return;
        }

        console.log('✅ Adding new message to UI:', message.id);
        this.addMessageToUI(message, true); // Scroll for new messages
        
        // Refresh conversations list (for staff)
        if (!this.isClient) {
            setTimeout(() => this.loadConversations(), 100);
        }
    }

    addMessageToUI(message, shouldScroll = true) {
        console.log('🔄 addMessageToUI called for message:', message.id, message.message);
        
        if (this.displayedMessageIds.has(message.id)) {
            console.log('⚠️ Duplicate message ignored:', message.id);
            return; // Prevent duplicates
        }

        this.displayedMessageIds.add(message.id);
        const container = document.getElementById('messagesContainer');
        
        if (!container) {
            console.error('❌ Messages container not found!');
            return;
        }
        
        console.log('✅ Adding message to UI:', message.id);
        
        // Remove empty state if present
        const emptyState = container.querySelector('.flex.justify-center.items-center.h-full');
        if (emptyState) {
            container.innerHTML = '';
        }

        const isOwn = message.user_id === this.currentUser.id;
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'} mb-4`;

        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="px-4 py-3 rounded-2xl shadow ${isOwn 
                    ? 'bg-blue-500 text-white ml-auto' 
                    : 'bg-white text-gray-900 border border-gray-200'
                }">
                    <p class="text-sm break-words">${this.escapeHtml(message.message)}</p>
                    <p class="text-xs mt-1 opacity-75">${time}</p>
                </div>
                ${!isOwn ? `<p class="text-xs text-gray-500 mt-1">${message.user.name}</p>` : ''}
            </div>
        `;

        container.appendChild(messageDiv);

        if (shouldScroll) {
            this.scrollToBottom();
        }
    }

    async sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();

        if (!message || !this.selectedUserId) {
            return;
        }

        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;

        try {
            console.log('📤 Sending message...', { to: this.selectedUserId, message: message.substring(0, 50) + '...' });

            const formData = new FormData();
            formData.append('sender_id', this.currentUser.id);
            formData.append('receiver_id', this.selectedUserId);
            formData.append('message', message);

            const response = await fetch('/api/client/chats/send-message', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.success && data.data) {
                    console.log('✅ Message sent successfully');
                    
                    // Clear input
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Set chat ID if this was the first message
                    if (!this.currentChatId && data.data.chat_id) {
                        this.currentChatId = data.data.chat_id;
                    }
                    
                    // The message will appear via SSE stream
                    
                } else {
                    throw new Error(data.message || 'Send failed');
                }
            } else {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }
        } catch (error) {
            console.error('❌ Error sending message:', error);
            this.showNotification('Failed to send message', 'error');
        } finally {
            sendButton.disabled = false;
        }
    }


    scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
    }

    showNotification(message, type = 'info') {
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

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Add fade-in animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);

    // Initialize chat manager
    new ChatManager();
});