<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Сообщения</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-gray-900 dark:text-gray-100">
                <div class="w-full flex flex-col md:flex-row gap-4">
                    @if(auth()->user()->role === 'admin' && count($conversations) > 0)
                    <!-- Conversations List (Admin Only) -->
                    <div class="w-full md:w-1/4 bg-[#191919] border border-[#2B2B2B] rounded-lg overflow-hidden">
                        <div class="p-4 border-b border-[#2B2B2B]">
                            <h3 class="font-semibold">Пользователи</h3>
                        </div>
                        <div class="overflow-y-auto max-h-[70vh]">
                            @foreach($conversations as $conversation)
                            <div 
                                class="conversation-item p-3 hover:bg-[#252525] cursor-pointer border-b border-[#2B2B2B] flex items-center"
                                data-conversation-id="{{ $conversation->id }}"
                                data-user-name="{{ $conversation->user->name }}"
                            >
                                <div class="flex items-center space-x-3 w-full">
                                    <div class="flex-shrink-0 relative">
                                        <!-- User avatar or placeholder -->
                                        <div class="w-10 h-10 rounded-full bg-[#6340FF] flex items-center justify-center text-white font-bold">
                                            {{ substr($conversation->user->name, 0, 1) }}
                                        </div>
                                        @if($conversation->unreadMessagesCount(auth()->id()) > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                            {{ $conversation->unreadMessagesCount(auth()->id()) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium truncate">{{ $conversation->user->name }}</p>
                                        @if($conversation->latestMessage)
                                        <p class="text-xs text-[#C2C2C2] truncate">{{ Str::limit($conversation->latestMessage->message, 30) }}</p>
                                        @else
                                        <p class="text-xs text-[#C2C2C2]">Нет сообщений</p>
                                        @endif
                                    </div>
                                    @if($conversation->last_message_at)
                                    <div class="text-xs text-[#C2C2C2]">
                                        {{ $conversation->last_message_at->diffForHumans() }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Main Chat Area -->
                    <div id="chat-container" class="w-full bg-[#191919] border border-[#2B2B2B] md:w-3/4 rounded-lg flex flex-col" style="height: 70vh;">
                        <!-- Chat header -->
                        <div class="p-4 border-b border-[#2B2B2B] dark:border-gray-700">
                            <h3 id="chat-header" class="font-semibold">Администрация</h3>
                        </div>

                        <!-- Chat messages -->
                        <div id="messages-container" class="flex-1 p-4 overflow-y-auto space-y-4 pr-2 hide-scrollbar">
                            <div class="flex justify-center">
                                <div class="bg-[#252525] text-[#C2C2C2] text-sm px-4 py-2 rounded-full">
                                    Начало переписки
                                </div>
                            </div>
                            <!-- Messages will be loaded here -->
                        </div>

                        <!-- Message input -->
                        <div class="p-4 border-t border-[#2B2B2B] dark:border-gray-700">
                            <form id="message-form" class="flex items-end gap-2">
                                <input type="hidden" id="conversation_id" name="conversation_id" value="{{ $conversations[0]->id ?? '' }}">
                                <input
                                   id="message-input"
                                   name="message"
                                   class="flex-1 text-white bg-[#191919] p-2 rounded-lg border-none resize-none focus:outline-none focus:ring-0 placeholder-gray-500"
                                   placeholder="Введите ваше сообщение..."
                                ></input>
                               
                                <!-- Text button, visible md+ -->
                                <button
                                   type="submit"
                                   class="hidden md:inline-flex hover:bg-[#6340FF] items-center bg-[#2B2B2B] text-white px-10 py-2 rounded-lg transition"
                                >
                                Отправить
                                </button>
                               
                                <button type="submit" class="p-2 inline-flex md:hidden bg-[#2B2B2B] text-white rounded-lg hover:bg-[#6340FF]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio for notifications -->
    <audio id="notification-sound" class="hidden">
        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
    </audio>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const conversationId = document.getElementById('conversation_id').value;
        const notificationSound = document.getElementById('notification-sound');
        const chatHeader = document.getElementById('chat-header');
        const userId = {{ auth()->id() }};
        const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};
        
        // Function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        // Function to scroll to bottom of messages
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Function to add a message to the chat
        function addMessage(message, isCurrentUser) {
            const messageElement = document.createElement('div');
            messageElement.className = isCurrentUser ? 
                'flex items-start gap-2 justify-end' : 
                'flex items-start gap-2';
            
            messageElement.innerHTML = `
                <div class="max-w-[75%] ${isCurrentUser ? 'bg-[#6340FF]' : 'bg-[#272727]'} p-3 rounded-lg">
                    <p class="text-sm">${message.message}</p>
                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">${formatDate(message.created_at)}</div>
                </div>
            `;
            
            messagesContainer.appendChild(messageElement);
            scrollToBottom();
        }
        
        // Load messages for the conversation
        function loadMessages(convoId) {
            fetch(`/chat/messages?conversation_id=${convoId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(messages => {
                messagesContainer.innerHTML = `
                    <div class="flex justify-center">
                        <div class="bg-[#252525] text-[#C2C2C2] text-sm px-4 py-2 rounded-full">
                            Начало переписки
                        </div>
                    </div>
                `;
                
                messages.forEach(message => {
                    addMessage(message, message.sender_id === userId);
                });
                
                scrollToBottom();
            })
            .catch(error => console.error('Error loading messages:', error));
        }
        
        // If we have a conversation ID, load messages
        if (conversationId) {
            loadMessages(conversationId);
        }
        
        // Handle form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            const currentConvoId = document.getElementById('conversation_id').value;
            
            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    conversation_id: currentConvoId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                // Clear input
                messageInput.value = '';
                
                // Add message to chat
                addMessage(data, true);
            })
            .catch(error => console.error('Error sending message:', error));
        });
        
        // Handle conversation selection (admin only)
        if (isAdmin) {
            const conversationItems = document.querySelectorAll('.conversation-item');
            conversationItems.forEach(item => {
                item.addEventListener('click', function() {
                    const convoId = this.dataset.conversationId;
                    const userName = this.dataset.userName;
                    
                    // Update active conversation
                    conversationItems.forEach(i => i.classList.remove('bg-[#252525]'));
                    this.classList.add('bg-[#252525]');
                    
                    // Update conversation ID in form
                    document.getElementById('conversation_id').value = convoId;
                    
                    // Update chat header
                    chatHeader.textContent = `Чат с ${userName}`;
                    
                    // Load messages
                    loadMessages(convoId);
                    
                    // Remove unread indicator if present
                    const unreadBadge = this.querySelector('.bg-red-500');
                    if (unreadBadge) {
                        unreadBadge.remove();
                    }
                });
            });
            
            // Set first conversation as active if available
            if (conversationItems.length > 0) {
                conversationItems[0].click();
            }
        }
        
        // Set up Echo for real-time messaging
        window.Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (e) => {
                // Only add message if it's from someone else
                if (e.sender_id !== userId) {
                    addMessage(e, false);
                    
                    // Play notification sound
                    notificationSound.play();
                    
                    // Show browser notification if supported and page is not visible
                    if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                        new Notification('Новое сообщение', {
                            body: e.message,
                            icon: '/favicon.ico'
                        });
                    }
                }
            });
        
        // Request notification permission
        if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    });
</script>
@endpush
