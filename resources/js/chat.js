/**
 * Real-time Chat Module for Laravel 12 + Ajax Polling
 * Clean implementation with proper real-time messaging using Ajax instead of SSE
 */

class ChatManager {
    constructor() {
        console.log('🔧 ChatManager constructor called');
        console.log('🔧 Environment info:', {
            hostname: window.location.hostname,
            protocol: window.location.protocol,
            origin: window.location.origin,
            userAgent: navigator.userAgent.substring(0, 50) + '...'
        });
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
        this.displayedMessageIds = new Set();
        this.searchTimeout = null;
        this.lastMessageId = 0;
        this.hasManualSelection = false; // Track if staff manually selected a conversation
        
        // Ajax polling configuration
        this.messagePollingInterval = null;
        this.conversationPollingInterval = null;
        this.messagePollingFrequency = 2000; // Poll messages every 2 seconds when in active chat
        this.conversationPollingFrequency = 5000; // Poll conversation updates every 5 seconds
        
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
        await this.loadConversations();
        this.startConversationPolling();
        
        // Debug: Test direct API call for clients
        if (this.isClient) {
            console.log('🔍 Testing direct API call for client...');
            await this.testClientMessageAPI();
        }
        
        console.log('✅ Chat Manager initialized');
    }

    async testClientMessageAPI() {
        try {
            console.log('🧪 Testing client message API with client ID:', this.currentUser.id);
            const response = await fetch(`/api/client/chats/messages/${this.currentUser.id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.ChatData?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            console.log('🧪 Direct API test - Status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('🧪 Direct API test - Success:', data.success);
                console.log('🧪 Direct API test - Messages count:', data?.data?.messages?.length || 0);
                console.log('🧪 Direct API test - Chat ID:', data?.data?.chat_id);
            } else {
                const errorText = await response.text();
                console.log('🧪 Direct API test - Error:', errorText);
            }
        } catch (error) {
            console.error('🧪 Direct API test - Exception:', error);
        }
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

        // Conversation clicks
        document.addEventListener('click', (e) => {
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                const userId = conversationItem.dataset.userId;
                const userName = conversationItem.dataset.userName;
                
                // For clients clicking on staff conversation, use client's own ID for message loading
                // but keep the staff name for display
                let messageLoadUserId = userId;
                if (this.isClient && conversationItem.dataset.isStaff === 'true') {
                    messageLoadUserId = this.currentUser.id;
                    // Store staff info for sending messages
                    window.ChatData.staff = {
                        id: parseInt(userId),
                        name: userName
                    };
                }
                
                console.log('🖱️ Manual conversation click:', {
                    clickedUserId: userId,
                    messageLoadUserId: messageLoadUserId,
                    userName: userName,
                    isClient: this.isClient,
                    isStaffConversation: conversationItem.dataset.isStaff === 'true'
                });
                
                this.selectConversation(messageLoadUserId, userName);
            }
        });

        // Handle page visibility for efficient polling
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Page hidden, reduce polling frequency
                this.pauseActivePolling();
            } else {
                // Page visible, resume normal polling
                this.resumeActivePolling();
            }
        });
    }

    startConversationPolling() {
        console.log('📊 Starting conversation polling...');
        this.conversationPollingInterval = setInterval(() => {
            this.pollConversationUpdates();
        }, this.conversationPollingFrequency);
    }

    startMessagePolling() {
        if (this.messagePollingInterval) {
            clearInterval(this.messagePollingInterval);
        }
        
        console.log('📨 Starting message polling for chat:', this.currentChatId);
        this.messagePollingInterval = setInterval(() => {
            this.pollNewMessages();
        }, this.messagePollingFrequency);
    }

    stopMessagePolling() {
        if (this.messagePollingInterval) {
            console.log('⏹️ Stopping message polling');
            clearInterval(this.messagePollingInterval);
            this.messagePollingInterval = null;
        }
    }

    pauseActivePolling() {
        console.log('⏸️ Pausing active polling (page hidden)');
        this.stopMessagePolling();
    }

    resumeActivePolling() {
        console.log('▶️ Resuming active polling (page visible)');
        if (this.currentChatId) {
            this.startMessagePolling();
        }
    }

    async pollNewMessages() {
        if (!this.currentChatId) {
            return;
        }

        try {
            const response = await fetch(`/chat/poll-messages/${this.currentChatId}?last_message_id=${this.lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.ChatData?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.success && data.has_new_messages && data.messages) {
                    console.log('📨 New messages received:', data.messages.length);
                    
                    data.messages.forEach(message => {
                        this.handleIncomingMessage(message);
                    });
                    
                    this.lastMessageId = data.last_message_id;
                }
            }
        } catch (error) {
            console.error('❌ Error polling new messages:', error);
        }
    }

    async pollConversationUpdates() {
        try {
            const response = await fetch(`/chat/poll-conversation-updates?last_message_id=${this.lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.ChatData?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.success && data.has_updates && data.conversation_updates) {
                    console.log('📊 Conversation updates received:', data.conversation_updates.length);
                    
                    // Update conversations list
                    this.handleConversationUpdates(data.conversation_updates);
                    
                    this.lastMessageId = Math.max(this.lastMessageId, data.last_message_id);
                }
            }
        } catch (error) {
            console.error('❌ Error polling conversation updates:', error);
        }
    }

    handleConversationUpdates(updates) {
        // For now, just reload conversations to keep it simple
        // In a more sophisticated implementation, you would update individual conversation items
        this.loadConversations();
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
            console.log('🔍 Client rendering conversation:', {
                hasStaff: !!data.staff,
                staffName: data.staff?.name,
                staffId: data.staff?.id,
                hasChats: !!data.chats,
                chatsCount: data.chats?.length || 0
            });
            
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
                    has_messages: existingChat && existingChat.latest_message ? true : false,
                    is_staff_conversation: true // Mark this as a staff conversation for clients
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
                        window.ChatData.staff = data.staff;
                        this.selectConversation(this.currentUser.id, data.staff.name, true);
                    }, 100);
                }
            }
        } else {
            // If current user is staff, show all clients (original behavior)
            if (data.chat_users && Array.isArray(data.chat_users)) {
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
            } else {
                console.log('⚠️ No chat_users array found in data:', data);
                this.showEmptyConversations();
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
        
        // Mark staff conversations for special handling
        if (conversation.is_staff_conversation) {
            div.dataset.isStaff = 'true';
        }

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
        
        // Start polling for new messages in this chat
        this.startMessagePolling();
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
        
        const noChatSelected = document.getElementById('noChatSelected');
        const activeChat = document.getElementById('activeChat');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        
        console.log('🔍 Elements found:', {
            noChatSelected: !!noChatSelected,
            activeChat: !!activeChat, 
            messageInput: !!messageInput,
            sendButton: !!sendButton
        });
        
        if (noChatSelected) {
            noChatSelected.classList.add('hidden');
            console.log('🔍 Hidden noChatSelected');
        }
        
        if (activeChat) {
            activeChat.classList.remove('hidden');
            activeChat.style.display = 'flex'; // Force display
            activeChat.style.zIndex = '1';     // Ensure z-index
            console.log('🔍 Shown activeChat, classes:', activeChat.className);
            console.log('🔍 ActiveChat style:', {
                display: activeChat.style.display,
                zIndex: activeChat.style.zIndex,
                visibility: activeChat.style.visibility
            });
        }
        
        // Update header
        const selectedUserName = document.getElementById('selectedUserName');
        const selectedUserAvatar = document.getElementById('selectedUserAvatar');
        
        if (selectedUserName) {
            selectedUserName.textContent = this.selectedUserName;
        }
        if (selectedUserAvatar) {
            selectedUserAvatar.textContent = this.selectedUserName.substring(0, 2);
        }
        
        // Ensure input and button are visible
        if (messageInput) {
            messageInput.style.zIndex = '100';
            messageInput.style.position = 'relative';
            console.log('🔍 MessageInput styled');
        }
        if (sendButton) {
            sendButton.style.zIndex = '101';
            sendButton.style.position = 'relative';
            console.log('🔍 SendButton styled');
        }
        
        console.log('✅ Chat area setup complete');
    }

    async loadMessages() {
        try {
            console.log(`📨 Loading messages for user ${this.selectedUserId}...`);
            console.log('🔍 Client debug info:', {
                selectedUserId: this.selectedUserId,
                currentUser: this.currentUser,
                isClient: this.isClient,
                selectedUserName: this.selectedUserName
            });
            
            // Show loading state
            const container = document.getElementById('messagesContainer');
            if (!container) {
                console.error('❌ messagesContainer not found!');
                return;
            }
            
            container.innerHTML = `
                <div class="flex justify-center items-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500">Loading messages...</span>
                </div>
            `;

            const response = await fetch(`/api/client/chats/messages/${this.selectedUserId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.ChatData?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            console.log('📨 API Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('📨 Messages API response:', data);
                console.log('🔍 API response details:', {
                    success: data?.success,
                    chatId: data?.data?.chat_id,
                    messagesCount: data?.data?.messages?.length || 0,
                    staff: data?.data?.staff?.name,
                    otherUser: data?.data?.other_user?.name,
                    firstMessage: data?.data?.messages?.[0]?.message?.substring(0, 30) + '...' || 'None'
                });
                
                if (data.success && data.data && data.data.messages) {
                    this.currentChatId = data.data.chat_id;
                    const messageCount = data.data.messages.length;
                    console.log(`🔄 About to display ${messageCount} messages for chat ${this.currentChatId}`);
                    
                    // Force clear any existing content
                    container.innerHTML = '';
                    
                    // Add a delay to ensure DOM is ready
                    await new Promise(resolve => setTimeout(resolve, 100));
                    
                    this.displayMessages(data.data.messages);
                    
                    // Set last message ID for polling
                    if (data.data.messages.length > 0) {
                        const lastMessage = data.data.messages[data.data.messages.length - 1];
                        this.lastMessageId = Math.max(this.lastMessageId, lastMessage.id);
                    }
                    
                    console.log(`✅ Loaded ${messageCount} messages for chat ${this.currentChatId}`);
                } else {
                    console.log('❌ API response indicates no data or empty messages:', data);
                    this.showEmptyMessages();
                }
            } else {
                const errorText = await response.text();
                console.error('❌ Failed to load messages:', response.status, errorText);
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
        console.log('🔍 Container element exists:', !!container);
        console.log('🔍 Messages array:', Array.isArray(messages), 'Length:', messages?.length || 0);
        
        // Force clear container and reset state
        container.innerHTML = '';
        this.displayedMessageIds.clear();

        if (!messages || !Array.isArray(messages) || messages.length === 0) {
            console.log('📭 No messages to display, showing empty state');
            this.showEmptyMessages();
            return;
        }

        console.log(`✅ Processing ${messages.length} messages for display`);
        
        // Create a document fragment for better performance
        const fragment = document.createDocumentFragment();
        
        messages.forEach((message, index) => {
            console.log(`🔄 Processing message ${index + 1}:`, {
                id: message.id,
                message: message.message?.substring(0, 50) + '...',
                user_id: message.user_id,
                created_at: message.created_at
            });
            
            if (!message.id || !message.message) {
                console.warn('⚠️ Skipping invalid message:', message);
                return;
            }
            
            this.displayedMessageIds.add(message.id);
            
            // Create message element
            const messageElement = this.createMessageElement(message);
            if (messageElement) {
                fragment.appendChild(messageElement);
            }
        });
        
        // Add all messages to container at once
        container.appendChild(fragment);
        
        console.log('🔍 Container after adding messages - HTML length:', container.innerHTML.length);
        console.log('🔍 Container children count:', container.children.length);

        // Force scroll after all messages are added
        setTimeout(() => {
            this.scrollToBottom();
            console.log('✅ Messages display complete');
        }, 100);
    }

    createMessageElement(message) {
        try {
            const isOwn = message.user_id === this.currentUser.id;
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'} mb-4 animate-fade-in`;

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
                    ${!isOwn ? `<p class="text-xs text-gray-500 mt-1">${this.escapeHtml(message.user?.name || 'Unknown User')}</p>` : ''}
                </div>
            `;

            return messageDiv;
        } catch (error) {
            console.error('❌ Error creating message element:', error, message);
            return null;
        }
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
        this.addIncomingMessageToUI(message);
        
        // Update last message ID
        this.lastMessageId = Math.max(this.lastMessageId, message.id);
    }

    addIncomingMessageToUI(message) {
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

        const messageElement = this.createMessageElement(message);
        if (messageElement) {
            container.appendChild(messageElement);
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
            console.log('📤 Sending message...', { 
                to: this.selectedUserId, 
                message: message.substring(0, 50) + '...',
                currentUser: this.currentUser,
                isClient: this.isClient
            });

            // Determine correct receiver_id based on user role
            let receiverId;
            if (this.isClient) {
                // Client always sends TO the staff member
                const staffData = window.ChatData?.staff || (await this.getStaffInfo());
                receiverId = staffData ? staffData.id : this.selectedUserId;
            } else {
                // Staff sends to the selected user (client)
                receiverId = this.selectedUserId;
            }

            const response = await fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.ChatData?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: message
                })
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.status === 'success' && data.message) {
                    console.log('✅ Message sent successfully');
                    
                    // Clear input
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Set chat ID if this was the first message
                    if (!this.currentChatId && data.message.chat_id) {
                        this.currentChatId = data.message.chat_id;
                        // Start message polling now that we have a chat
                        this.startMessagePolling();
                    }
                    
                    // Add message to UI immediately for better UX
                    this.addIncomingMessageToUI(data.message);
                    
                } else {
                    console.error('❌ Send message failed:', data);
                    throw new Error(data.message || 'Send failed');
                }
            } else {
                let errorData = null;
                try {
                    errorData = await response.json();
                } catch (e) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                
                console.error('❌ HTTP error:', response.status, errorData);
                throw new Error(`HTTP ${response.status}: ${errorData?.message || 'Unknown error'}`);
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

    async getStaffInfo() {
        try {
            const response = await fetch('/api/client/chats', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                return data.success && data.data ? data.data.staff : null;
            }
        } catch (error) {
            console.error('❌ Error getting staff info:', error);
        }
        return null;
    }

    // Cleanup method for when component is destroyed
    destroy() {
        this.stopMessagePolling();
        
        if (this.conversationPollingInterval) {
            clearInterval(this.conversationPollingInterval);
            this.conversationPollingInterval = null;
        }
        
        console.log('🗑️ ChatManager destroyed');
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
    window.chatManager = new ChatManager();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.chatManager) {
        window.chatManager.destroy();
    }
});