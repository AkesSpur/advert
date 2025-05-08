<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the admin chat interface.
     */
    public function index()
    {
        $conversations = Conversation::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
            
        // Get all users for the new message feature
        $allUsers = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
            
        return view('admin.messenger.index', compact('conversations', 'allUsers'));
    }
    
    /**
     * Get messages for a conversation.
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);
        
        $user = Auth::user();
        $conversationId = $request->conversation_id;
        
        // Ensure user is admin
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Mark messages as read
        ChatMessage::where('conversation_id', $conversationId)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Get messages
        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }
    
    /**
     * Send a message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Ensure user is admin
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $conversationId = $request->conversation_id;
        $message = $request->message;
        
        // Get conversation
        $conversation = Conversation::findOrFail($conversationId);
        
        // Create message
        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_id' => $user->id,
            'receiver_id' => $conversation->user_id,
            'message' => $message,
        ]);
        
        // Update conversation last message timestamp
        $conversation->update(['last_message_at' => now()]);
        
        // Broadcast the message
        broadcast(new MessageSent($chatMessage))->toOthers();
        
        return response()->json($chatMessage->load('sender'));
    }
    
    /**
     * Create or get a conversation with a user.
     */
    public function createConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $admin = Auth::user();
        
        // Ensure user is admin
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $userId = $request->user_id;
        
        // Check if conversation already exists
        $conversation = Conversation::where('user_id', $userId)
            ->where('admin_id', $admin->id)
            ->first();
            
        // Create new conversation if it doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'admin_id' => $admin->id,
                'last_message_at' => now(),
            ]);
        }
        
        return response()->json([
            'conversation_id' => $conversation->id,
            'user_name' => $conversation->user->name
        ]);
    }
}