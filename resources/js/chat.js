import './bootstrap';

// Echo and Pusher are initialized in bootstrap.js

// Function to setup Echo for a conversation
function setupEcho(conversationId) {
    // Check if Echo is initialized
    if (!window.Echo) {
        console.error('Laravel Echo is not initialized. Check your Pusher configuration.');
        return;
    }
    
    // Remove any existing listeners first
    if (window.currentChannel) {
        window.currentChannel.stopListening('.message.sent');
    }
    
    // Initialize Echo for private channels
    window.currentChannel = window.Echo.private(`chat.${conversationId}`)
        .listen('.message.sent', (data) => {
            // Add the new message to the chat
            appendMessage(data.message);
            
            // Play notification sound
            playNotificationSound();
            
            // Show browser notification
            showNotification(data.message.sender.name, data.message.message);
            
            // Scroll to bottom
            scrollToBottom();
        });
}

// Function to append a new message to the chat
function appendMessage(message) {
    const isCurrentUser = message.sender_id === currentUserId;
    const messageHtml = createMessageHtml(message, isCurrentUser);
    
    // Add message to the chat container
    const chatContent = document.querySelector('.chat-content');
    if (chatContent) {
        chatContent.innerHTML += messageHtml;
    }
}

// Function to create HTML for a message
function createMessageHtml(message, isCurrentUser) {
    const time = formatTime(message.created_at);
    const alignClass = isCurrentUser ? 'justify-end' : '';
    const bgClass = isCurrentUser ? 'bg-[#6340FF]' : 'bg-[#272727]';
    
    // Handle both 'content' and 'message' field names for compatibility
    const messageContent = message.content || message.message;
    
    return `
        <div class="flex items-start gap-2 ${alignClass}">
            <div class="max-w-[75%] ${bgClass} p-3 rounded-lg">
                <p class="text-sm">${messageContent}</p>
                <div class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">${time}</div>
            </div>
        </div>
    `;
}

// Format time for display
function formatTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Scroll chat to bottom
function scrollToBottom() {
    const chatContent = document.querySelector('.chat-content');
    if (chatContent) {
        chatContent.scrollTop = chatContent.scrollHeight;
    }
}

// Play notification sound
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.play().catch(e => console.error('Error playing notification sound:', e));
}

// Show browser notification
function showNotification(sender, message) {
    // Check if browser notifications are supported and permission is granted
    if (!('Notification' in window)) {
        console.log('This browser does not support notifications');
        return;
    }
    
    if (Notification.permission === 'granted') {
        new Notification('New message from ' + sender, {
            body: message,
            icon: '/favicon.ico'
        });
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                showNotification(sender, message);
            }
        });
    }
}

// Request notification permission on page load
document.addEventListener('DOMContentLoaded', () => {
    if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
    
    // Scroll to bottom on page load
    scrollToBottom();
    
    // Add event listener for message form submission
    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });
    }
    
    // Initialize Echo for the current conversation if conversationId is available
    if (typeof conversationId !== 'undefined') {
        setupEcho(conversationId);
    }
});

// Send message function
function sendMessage() {
    const messageInput = document.querySelector('.message-box');
    const message = messageInput.value.trim();
    
    if (message === '') return;
    
    // Clear input field
    messageInput.value = '';
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Determine the endpoint based on the user role
    const isAdmin = typeof isAdminUser !== 'undefined' ? isAdminUser : false;
    const endpoint = isAdmin ? '/admin/chat/send' : '/chat/send';
    
    // Prepare the request body
    const requestBody = {
        conversation_id: conversationId,
        // Use 'message' or 'content' based on the controller expectation
        message: message,
        content: message
    };
    
    // Send message to server
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
        // Message will be added by the Echo listener
        // But we can add it immediately for better UX
        appendMessage(data);
        scrollToBottom();
    })
    .catch(error => {
        console.error('Error sending message:', error);
    });
}