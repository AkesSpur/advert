<x-app-layout>
    <x-pusher-config />
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Сообщения</h1>
    </div>
    <div class="pb-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-gray-900 dark:text-gray-100">
                <div class="w-full flex flex-col md:flex-row gap-4">
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
                            <form id="message-form" class="flex items-end gap-2" method="POST" action="javascript:void(0);">
                                <input type="hidden" id="conversation_id" name="conversation_id" value="{{ $conversation->id ?? '' }}">
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
        <source src="{{ asset('sounds/notifications.mp3') }}" type="audio/mpeg">
    </audio>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const conversationIdInput = document.getElementById('conversation_id');
        const notificationSound = document.getElementById('notification-sound');
        const userId = {{ auth()->id() }};
        let activeConversationId = "{{ $conversation->id ?? '' }}";
        
        // Function to format date
        function formatDateTime(dateTimeString) {
            const options = {
                hour: '2-digit',
                minute: '2-digit'
            }
            return new Intl.DateTimeFormat('ru', options).format(new Date(dateTimeString));
        }
        
        // Function to scroll to bottom of messages
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Load messages for the conversation
        function loadMessages() {
            if (!activeConversationId) return;
            
            fetch(`/user/chat/messages?conversation_id=${activeConversationId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(messages => {
                // Clear existing messages except the "conversation started" message
                messagesContainer.innerHTML = `
                    <div class="flex justify-center">
                        <div class="bg-[#252525] text-[#C2C2C2] text-sm px-4 py-2 rounded-full">
                            Начало переписки
                        </div>
                    </div>
                `;
                
                // Add messages
                messages.forEach(message => {
                    addMessage(message, message.sender_id === userId);
                });
                
                scrollToBottom();
                
                // Set up Echo for this conversation
                setupEcho(activeConversationId);
            })
            .catch(error => console.error('Error loading messages:', error));
        }
        
        // Add a message to the chat
        function addMessage(message, isCurrentUser) {
            const alignClass = isCurrentUser ? 'justify-end' : '';
            const bgClass = isCurrentUser ? 'bg-[#6340FF]' : 'bg-[#272727]';
            
            const messageHtml = `
                <div class="flex items-start gap-2 ${alignClass}">
                    <div class="max-w-[75%] ${bgClass} p-3 rounded-lg">
                        <p class="text-sm">${message.message}</p>
                        <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">${formatDateTime(message.created_at)}</div>
                    </div>
                </div>
            `;
            
            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
            scrollToBottom();
        }
        
        // Handle form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message || !activeConversationId) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/user/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    conversation_id: activeConversationId,
                    message: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear input
                messageInput.value = '';
                
                // Add message to chat
                addMessage(data, true);
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Не удалось отправить сообщение. Пожалуйста, попробуйте еще раз.');
            });
        });
        
        // Set up Echo for real-time messaging
        function setupEcho(conversationId) {
            // First unsubscribe from any existing channels
            if (window.Echo && window.currentChatChannel) {
                window.Echo.leave(window.currentChatChannel);
            }
            
            // Set current channel name
            window.currentChatChannel = `chat.${conversationId}`;
            
            // Subscribe to new channel
            if (window.Echo) {
                window.Echo.private(`chat.${conversationId}`)
                    .listen('MessageSent', (e) => {
                        console.log('Message received:', e);
                        // Only add message if it's from someone else
                        if (e.sender_id !== userId) {
                            addMessage(e, false);
                            
                            // Play notification sound
                            notificationSound.play().catch(err => console.error('Error playing sound:', err));
                            
                            // Show browser notification if supported and page is not visible
                            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                                new Notification('Новое сообщение', {
                                    body: e.message,
                                    icon: '/favicon.ico'
                                });
                            }
                        }
                    });
            } else {
                console.error('Echo is not initialized');
            }
        }
        
        // Request notification permission
        if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
        
        // Check if we have a valid conversation ID
        if (activeConversationId) {
            console.log('Loading messages for conversation:', activeConversationId);
            // Load messages on page load
            loadMessages();
        } else {
            console.error('No active conversation ID found');
        }
    });
</script>
</x-app-layout>
