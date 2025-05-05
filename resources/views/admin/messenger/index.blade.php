@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Messages</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Components</a></div>
        <div class="breadcrumb-item">Chat Box</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-3">
          <div class="card" style="height: 70vh;">
            <div class="card-header">
              <h4>Users</h4>
            </div>
            <div class="card-body conversation-list">
              <ul class="list-unstyled list-unstyled-border">
                @foreach ($conversations as $conversation)
                @php
                    $unreadCount = $conversation->unreadMessagesCount(auth()->id());
                @endphp
                <li class="media conversation-item" 
                    data-id="{{ $conversation->id }}" 
                    data-user-name="{{ $conversation->user->name }}">
                  <div class="mr-3 position-relative">
                    <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-weight: bold;">
                      {{ substr($conversation->user->name, 0, 1) }}
                    </div>
                    @if($unreadCount > 0)
                    <span class="badge badge-danger badge-pill position-absolute" style="top: -5px; right: -5px;">
                      {{ $unreadCount }}
                    </span>
                    @endif
                  </div>
                  <div class="media-body">
                    <div class="mt-0 mb-1 font-weight-bold">{{ $conversation->user->name }}</div>
                    @if($conversation->latestMessage)
                    <div class="text-small text-muted text-truncate" style="max-width: 150px;">
                      {{ Str::limit($conversation->latestMessage->message, 30) }}
                    </div>
                    <div class="text-small text-muted">
                      {{ $conversation->last_message_at->diffForHumans() }}
                    </div>
                    @else
                    <div class="text-small text-muted">No messages yet</div>
                    @endif
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card chat-box" id="mychatbox" style="height: 70vh;">
            <div class="card-header">
              <h4 id="chat-inbox-title">Select a conversation</h4>
            </div>
            <div class="card-body chat-content" id="messages-container">
              <!-- Messages will be loaded here -->
            </div>
            <div class="card-footer chat-form">
              <form id="message-form">
                <input type="text" class="form-control message-box" placeholder="Type a message" name="message" id="message-input">
                <input type="hidden" name="conversation_id" value="" id="conversation_id">
                <button type="submit" class="btn btn-primary">
                  <i class="far fa-paper-plane"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Audio for notifications -->
  <audio id="notification-sound" style="display: none;">
    <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
  </audio>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const conversationIdInput = document.getElementById('conversation_id');
    const notificationSound = document.getElementById('notification-sound');
    const chatHeader = document.getElementById('chat-inbox-title');
    const userId = {{ auth()->id() }};
    let activeConversationId = null;
    
    // Function to format date
    function formatDateTime(dateTimeString) {
      const options = {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      }
      const formatedDateTime = new Intl.DateTimeFormat('en-Us', options).format(new Date(dateTimeString));
      return formatedDateTime;
    }
    
    // Function to scroll to bottom of messages
    function scrollToBottom() {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Load messages for a conversation
    function loadMessages(conversationId) {
      if (!conversationId) return;
      
      activeConversationId = conversationId;
      conversationIdInput.value = conversationId;
      
      fetch(`/admin/chat/messages?conversation_id=${conversationId}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        }
      })
      .then(response => response.json())
      .then(messages => {
        messagesContainer.innerHTML = `
          <div class="text-center my-3">
            <span class="badge badge-light">Conversation started</span>
          </div>
        `;
        
        messages.forEach(message => {
          addMessage(message, message.sender_id === userId);
        });
        
        scrollToBottom();
        
        // Set up Echo for this conversation
        setupEcho(conversationId);
      })
      .catch(error => console.error('Error loading messages:', error));
    }
    
    // Add a message to the chat
    function addMessage(message, isCurrentUser) {
      const messageHtml = isCurrentUser ? 
        `<div class="chat-item chat-right">
          <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-weight: bold;">
            ${message.sender ? message.sender.name.substr(0, 1) : 'A'}
          </div>
          <div class="chat-details">
            <div class="chat-text">${message.message}</div>
            <div class="chat-time">${formatDateTime(message.created_at)}</div>
          </div>
        </div>` : 
        `<div class="chat-item chat-left">
          <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-weight: bold;">
            ${message.sender ? message.sender.name.substr(0, 1) : 'U'}
          </div>
          <div class="chat-details">
            <div class="chat-text">${message.message}</div>
            <div class="chat-time">${formatDateTime(message.created_at)}</div>
          </div>
        </div>`;
      
      messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
      scrollToBottom();
    }
    
    // Handle conversation selection
    const conversationItems = document.querySelectorAll('.conversation-item');
    conversationItems.forEach(item => {
      item.addEventListener('click', function() {
        const conversationId = this.dataset.id;
        const userName = this.dataset.userName;
        
        // Update active conversation
        conversationItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // Update chat header
        chatHeader.textContent = `Chat with ${userName}`;
        
        // Load messages
        loadMessages(conversationId);
        
        // Remove unread indicator if present
        const unreadBadge = this.querySelector('.badge-danger');
        if (unreadBadge) {
          unreadBadge.remove();
        }
      });
    });
    
    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const message = messageInput.value.trim();
      if (!message || !activeConversationId) return;
      
      fetch('/admin/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          conversation_id: activeConversationId,
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
    
    // Set up Echo for real-time messaging
    function setupEcho(conversationId) {
      // First unsubscribe from any existing channels
      if (window.Echo && window.currentChatChannel) {
        window.Echo.leave(window.currentChatChannel);
      }
      
      // Set current channel name
      window.currentChatChannel = `chat.${conversationId}`;
      
      // Subscribe to new channel
      window.Echo.private(`chat.${conversationId}`)
        .listen('.message.sent', (e) => {
          // Only add message if it's from someone else
          if (e.sender_id !== userId) {
            addMessage(e, false);
            
            // Play notification sound
            notificationSound.play();
            
            // Show browser notification if supported and page is not visible
            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
              new Notification('New Message', {
                body: e.message,
                icon: '/favicon.ico'
              });
            }
          }
        });
    }
    
    // Set first conversation as active if available
    if (conversationItems.length > 0) {
      conversationItems[0].click();
    }
    
    // Request notification permission
    if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
      Notification.requestPermission();
    }
  });
</script>
@endpush
