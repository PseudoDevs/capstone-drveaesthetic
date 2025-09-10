/**
 * Real-time Chat Module for Laravel 12 + Pusher
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
        this.pusherChannel = null;
        this.displayedMessageIds = new Set();
        this.searchTimeout = null;
        
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
        this.setupPusher();
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

        // Search functionality (staff only)
        if (!this.isClient) {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
                
                // Click outside to hide search results
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
                        this.hideSearchResults();
                    }
                });
            }
        }

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

    setupPusher() {
        if (!window.pusher) {
            console.error('❌ Pusher not initialized');
            return;
        }

        console.log('🔄 Setting up Pusher connection...');
        
        window.pusher.connection.bind('connected', () => {
            console.log('✅ Pusher connected');
        });

        window.pusher.connection.bind('error', (error) => {
            console.error('❌ Pusher connection error:', error);
        });
    }

    async loadConversations() {
        if (this.isClient) {
            // Clients don't need to load conversations - they see staff directly
            return;
        }

        try {
            console.log('📨 Loading conversations...');
            
            const response = await fetch('/chat/conversations', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.ChatData.csrfToken
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderConversations(data.conversations);
            } else {
                console.error('❌ Failed to load conversations:', response.status);
                this.showEmptyConversations();
            }
        } catch (error) {
            console.error('❌ Error loading conversations:', error);
            this.showEmptyConversations();
        }
    }

    renderConversations(conversations) {
        const container = document.getElementById('conversationsList');
        
        if (!conversations || conversations.length === 0) {
            this.showEmptyConversations();
            return;
        }

        container.innerHTML = '';
        
        conversations.forEach(conversation => {
            const element = this.createConversationElement(conversation);
            container.appendChild(element);
        });

        console.log(`✅ Rendered ${conversations.length} conversations`);
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

    async selectConversation(userId, userName) {
        console.log(`💬 Selecting conversation with ${userName} (${userId})`);
        
        this.selectedUserId = parseInt(userId);
        this.selectedUserName = userName;

        // Update UI selection
        this.updateConversationSelection();
        
        // Show chat area
        this.showChatArea();
        
        // Load messages
        await this.loadMessages();
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

            const response = await fetch(`/chat/messages/${this.selectedUserId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.ChatData.csrfToken
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.currentChatId = data.chat_id;
                this.displayMessages(data.messages || []);
                this.setupChatChannel();
                console.log(`✅ Loaded ${data.messages?.length || 0} messages for chat ${this.currentChatId}`);
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
        
        container.innerHTML = '';
        this.displayedMessageIds.clear();

        if (!messages || messages.length === 0) {
            this.showEmptyMessages();
            return;
        }

        console.log(`✅ Displaying ${messages.length} messages`);
        
        messages.forEach(message => {
            this.displayedMessageIds.add(message.id);
            this.addMessageToUI(message, false); // Don't scroll for initial load
        });

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

    setupChatChannel() {
        if (!window.pusher || !this.currentChatId) {
            console.error('❌ Cannot setup chat channel:', { pusher: !!window.pusher, chatId: this.currentChatId });
            return;
        }

        // Unsubscribe from previous channel
        if (this.pusherChannel) {
            this.pusherChannel.unbind_all();
            window.pusher.unsubscribe(this.pusherChannel.name);
        }

        // Subscribe to chat channel
        const channelName = `private-chat.${this.currentChatId}`;
        console.log(`🔄 Subscribing to ${channelName}...`);
        
        this.pusherChannel = window.pusher.subscribe(channelName);

        this.pusherChannel.bind('message.sent', (data) => {
            console.log('📨 Received message via Pusher:', data);
            this.handleIncomingMessage(data.message);
        });

        this.pusherChannel.bind('pusher:subscription_succeeded', () => {
            console.log(`✅ Successfully subscribed to ${channelName}`);
        });

        this.pusherChannel.bind('pusher:subscription_error', (error) => {
            console.error('❌ Pusher subscription error:', error);
        });
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
        if (this.displayedMessageIds.has(message.id)) {
            return; // Prevent duplicates
        }

        this.displayedMessageIds.add(message.id);
        const container = document.getElementById('messagesContainer');
        
        if (!container) {
            console.error('❌ Messages container not found!');
            return;
        }
        
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

            const response = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.ChatData.csrfToken
                },
                body: JSON.stringify({
                    receiver_id: this.selectedUserId,
                    message: message
                })
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.status === 'success') {
                    console.log('✅ Message sent successfully');
                    
                    // Clear input
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Set chat ID if this was the first message
                    if (!this.currentChatId && data.message.chat_id) {
                        this.currentChatId = data.message.chat_id;
                        this.setupChatChannel();
                    }
                    
                    // The message will appear via Pusher broadcast
                    
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

    handleSearch(query) {
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        if (!query.trim()) {
            this.hideSearchResults();
            return;
        }

        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    async performSearch(query) {
        try {
            console.log('🔍 Searching for:', query);

            const response = await fetch(`/api/client/chats/search/staff?query=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.ChatData.csrfToken
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.showSearchResults(data.data || []);
            } else {
                console.error('❌ Search failed:', response.status);
                this.showSearchResults([]);
            }
        } catch (error) {
            console.error('❌ Search error:', error);
            this.showSearchResults([]);
        }
    }

    showSearchResults(users) {
        const resultsContainer = document.getElementById('searchResults');
        resultsContainer.innerHTML = '';

        if (users.length === 0) {
            resultsContainer.innerHTML = `
                <div class="p-3 text-center text-gray-500">
                    <p class="text-sm">No staff found</p>
                </div>
            `;
        } else {
            users.forEach(user => {
                const item = document.createElement('div');
                item.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0';
                item.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            ${user.name.substring(0, 2)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">${user.name}</h3>
                            <p class="text-xs text-gray-500 truncate">${user.email}</p>
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">${user.role}</span>
                        </div>
                    </div>
                `;
                
                item.addEventListener('click', () => {
                    this.selectConversation(user.id, user.name);
                    this.hideSearchResults();
                    document.getElementById('searchInput').value = '';
                });

                resultsContainer.appendChild(item);
            });
        }

        resultsContainer.classList.remove('hidden');
    }

    hideSearchResults() {
        const resultsContainer = document.getElementById('searchResults');
        if (resultsContainer) {
            resultsContainer.classList.add('hidden');
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