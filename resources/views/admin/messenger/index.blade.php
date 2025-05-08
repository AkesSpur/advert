@extends('admin.layouts.master')

@section('content')
<x-pusher-config />

<style>
  .user-item {
    cursor: pointer;
    transition: background-color 0.2s;
  }
  .user-item:hover {
    background-color: #f8f9fa;
  }
  .modal {
    z-index: 1050 !important;
  }
  .modal-backdrop {
    z-index: 1040 !important;
  }
  .conversation-list {
    overflow-y: auto;
    max-height: calc(70vh - 60px);
  }
  .chat-container {
    display: none;
  }
  .conversation-container {
    display: block;
  }
  .back-button {
    margin-right: 10px;
    cursor: pointer;
  }
  @media (max-width: 767.98px) {
    .new-message-btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
    .new-message-btn i {
      margin-right: 0;
    }
    .new-message-text {
      display: none;
    }
  }
</style>
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
        <div class="col-12 conversation-container" id="conversation-container">
          <div class="card" style="height: 70vh;">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>Conversations</h4>
              <button class="btn btn-sm btn-primary new-message-btn" id="show-all-users-btn"><i class="fas fa-plus"></i> <span class="new-message-text">New Message</span></button>
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
        <div class="col-12 chat-container" id="chat-container">
          <div class="card chat-box" id="mychatbox" style="height: 70vh;">
            <div class="card-header d-flex align-items-center">
              <div class="back-button" id="back-to-conversations">
                <i class="fas fa-arrow-left"></i>
              </div>
              <h4 id="chat-inbox-title">Select a conversation</h4>
            </div>
            <div class="card-body chat-content w-max" id="messages-container">
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

   <!-- All Users Modal -->
   <div class="modal fade" id="allUsersModal" tabindex="-1" role="dialog" aria-labelledby="allUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="allUsersModalLabel">Select User to Message</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" id="userSearchInput" placeholder="Search users...">
          </div>
          <ul class="list-unstyled list-unstyled-border" id="allUsersList">
            @foreach ($allUsers as $user)
              @if($user->id != auth()->id())
              <li class="media user-item" data-id="{{ $user->id }}" data-name="{{ $user->name }}" style="cursor: pointer;">
                <div class="mr-3">
                  <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-weight: bold;">
                    {{ substr($user->name, 0, 1) }}
                  </div>
                </div>
                <div class="media-body">
                  <div class="mt-0 mb-1 font-weight-bold">{{ $user->name }}</div>
                  <div class="text-small text-muted">{{ $user->email }}</div>
                </div>
              </li>
              @endif
            @endforeach
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Audio for notifications -->
  <audio id="notification-sound" style="display: none;">
    <source src="{{ asset('sounds/notifications.mp3') }}" type="audio/mpeg">
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
    const showAllUsersBtn = document.getElementById('show-all-users-btn');
    const userSearchInput = document.getElementById('userSearchInput');
    const allUsersList = document.getElementById('allUsersList');
    const userId = {{ auth()->id() }};
    let activeConversationId = null;
    
    // Function to format date
    function formatDateTime(dateTimeString) {
      const options = {
        // year: 'numeric',
        // month: 'short',
        // day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      }
      const formatedDateTime = new Intl.DateTimeFormat('ru-Ru', options).format(new Date(dateTimeString));
      return formatedDateTime;
    }
    
    // Function to scroll to bottom of messages
    function scrollToBottom() {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Load messages for a conversation
    function loadMessages(conversationId) {
      if (!conversationId) return;
      
      // Clear previous messages immediately when switching conversations
      messagesContainer.innerHTML = `
        <div class="text-center my-3">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      `;
      
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
        setupEcho(conversationId, userId);
      })
      .catch(error => console.error('Error loading messages:', error));
    }
    
    // Add a message to the chat
    function addMessage(message, isCurrentUser) {
      const messageHtml = isCurrentUser ? 
        `<div class="chat-item chat-right">
          <div class="chat-details">
            <div class="chat-text" style="max-width: 75%;">${message.message}</div>
            <div class="chat-time">${formatDateTime(message.created_at)}</div>
          </div>
        </div>` : 
        `<div class="chat-item chat-left">
          <div class="chat-details">
            <div class="chat-text" style="max-width: 75%;">${message.message}</div>
            <div class="chat-time">${formatDateTime(message.created_at)}</div>
          </div>
        </div>`;
      
      messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
      scrollToBottom();
    }
    
    // Handle conversation selection
    const conversationItems = document.querySelectorAll('.conversation-item');
    const conversationContainer = document.getElementById('conversation-container');
    const chatContainer = document.getElementById('chat-container');
    const backButton = document.getElementById('back-to-conversations');
    
    // Function to show chat and hide conversations
    function showChat() {
      conversationContainer.style.display = 'none';
      chatContainer.style.display = 'block';
    }
    
    // Function to show conversations and hide chat
    function showConversations() {
      conversationContainer.style.display = 'block';
      chatContainer.style.display = 'none';
    }
    
    // Initialize with conversations visible
    showConversations();
    
    // Handle back button click
    backButton.addEventListener('click', function() {
      showConversations();
    });
    
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
        
        // Show chat view
        showChat();
      });
    });
    
    // Handle form submission with debounce to prevent double submissions
    let isSubmitting = false;
    messageForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Prevent double submission
      if (isSubmitting) return;
      
      const message = messageInput.value.trim();
      if (!message || !activeConversationId) return;
      
      // Set submitting flag and disable input
      isSubmitting = true;
      messageInput.disabled = true;
      
      // Store message and clear input immediately for better UX
      const messageToSend = message;
      messageInput.value = '';
      
      fetch('/admin/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          conversation_id: activeConversationId,
          message: messageToSend
        })
      })
      .then(response => response.json())
      .then(data => {
        // Add message to chat
        addMessage(data, true);
      })
      .catch(error => {
        console.error('Error sending message:', error);
        // Restore message to input if sending failed
        messageInput.value = messageToSend;
      })
      .finally(() => {
        // Reset submission state
        isSubmitting = false;
        messageInput.disabled = false;
        messageInput.focus();
      });
    });
    });
    
    // Set up Echo for real-time messaging
    function setupEcho(conversationId, userId) {
      // First unsubscribe from any existing channels
      if (window.Echo && window.currentChatChannel) {
        window.Echo.leave(window.currentChatChannel);
      }
      
      // Set current channel name
      window.currentChatChannel = `chat.${conversationId}`;
      
      // Debug Echo connection
      console.log('Setting up Echo for channel:', window.currentChatChannel);
      
      if (!window.Echo) {
        console.error('Echo is not initialized. Check Pusher configuration.');
        return;
      }
      
      // Subscribe to new channel
      window.Echo.private(`chat.${conversationId}`)
        .listen('MessageSent', (e) => {
          console.log('Received message event:', e);
          // Extract the message data from the event
          // The backend sends the message data in the root of the event
          const messageData = e;
          
         
          // Only add message if it's from someone else
          if (messageData.sender_id !== userId) {
            addMessage(messageData, false);
            
            // Play notification sound
            try {
              notificationSound.play().catch(err => console.error('Error playing notification sound:', err));
            } catch (error) {
              console.error('Error playing notification sound:', error);
            }
            
            // Show browser notification if supported and page is not visible
            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
              new Notification('New Message', {
                body: messageData.message,
                icon: '/favicon.ico'
              });
            }
          }
        })
        .error((error) => {
          console.error('Echo connection error:', error);
        });
    }
    
    // Don't automatically select the first conversation
    // User must explicitly select a conversation to start chatting
    
    // Request notification permission
    if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
      Notification.requestPermission();
    }
    
    // Show all users modal
    document.getElementById('show-all-users-btn').addEventListener('click', () => {
  $('#allUsersModal').modal('show');
});
    
    // Close modal when close button is clicked
    document.querySelector('.modal .close').addEventListener('click', function() {
      $('#allUsersModal').modal('hide');
    });
    
    // Filter users in the modal
    userSearchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const userItems = allUsersList.querySelectorAll('.user-item');
      
      userItems.forEach(item => {
        const userName = item.dataset.name.toLowerCase();
        if (userName.includes(searchTerm)) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    });
    
    // Function to handle user selection and create conversation
    function createConversationWithUser(userId, userName) {
      // Show loading indicator
      const loadingHtml = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';
      $('#allUsersList').append(loadingHtml);
      
      // Create or get conversation with this user
      fetch('/admin/chat/create-conversation', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          user_id: userId
        })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Close the modal
        $('#allUsersModal').modal('hide');
        
        // Check if conversation already exists in the list
        const existingConversation = document.querySelector(`.conversation-item[data-id="${data.conversation_id}"]`);
        
        if (existingConversation) {
          // If it exists, click on it
          existingConversation.click();
        } else {
          // If it's a new conversation, we need to add it to the list and select it
          const newConversationHtml = `
            <li class="media conversation-item active" data-id="${data.conversation_id}" data-user-name="${data.user_name}">
              <div class="mr-3 position-relative">
                <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-weight: bold;">
                  ${data.user_name.substr(0, 1)}
                </div>
              </div>
              <div class="media-body">
                <div class="mt-0 mb-1 font-weight-bold">${data.user_name}</div>
                <div class="text-small text-muted">No messages yet</div>
              </div>
            </li>
          `;
          
          // Add to the conversation list
          document.querySelector('.conversation-list ul').insertAdjacentHTML('afterbegin', newConversationHtml);
          
          // Update active conversation
          const conversationItems = document.querySelectorAll('.conversation-item');
          conversationItems.forEach(i => i.classList.remove('active'));
          const newConversationItem = document.querySelector(`.conversation-item[data-id="${data.conversation_id}"]`);
          newConversationItem.classList.add('active');
          
          // Add click event to the new conversation item
          newConversationItem.addEventListener('click', function() {
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
            
            // Show chat view
            showChat();
          });
          
          // Load the conversation
          chatHeader.textContent = `Chat with ${data.user_name}`;
          loadMessages(data.conversation_id);
          
          // Show chat view
          showChat();
        }
      })
      .catch(error => {
        console.error('Error creating conversation:', error);
        alert('There was an error creating the conversation. Please try again.');
        $('#allUsersModal').modal('hide');
      })
      .finally(() => {
        // Remove loading indicator
        $('#allUsersList .spinner-border').parent().remove();
      });
    }
    
    // Handle user selection via click events
    $(document).on('click', '.user-item', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const userId = $(this).data('id');
      const userName = $(this).data('name');
      
      createConversationWithUser(userId, userName);
    });
    
    // Also handle direct selection for initial user items
    const userItems = document.querySelectorAll('.user-item');
    userItems.forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const userId = this.dataset.id;
        const userName = this.dataset.name;
        
        createConversationWithUser(userId, userName);
      });
    });
    
    // Ensure modal can be closed with ESC key
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape' && $('#allUsersModal').hasClass('show')) {
        $('#allUsersModal').modal('hide');
      }
    });
  


</script>
@endpush
