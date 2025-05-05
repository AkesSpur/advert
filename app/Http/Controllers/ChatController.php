<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = [];
        
        // If user is admin, get all conversations
        if ($user->role === 'admin') {
            $conversations = Conversation::with(['user', 'latestMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();
        } else {
            // For regular users, get only their conversation with admin
            $conversation = Conversation::with(['admin', 'latestMessage'])
                ->where('user_id', $user->id)
                ->first();
                
            // Create conversation if it doesn't exist
            if (!$conversation) {
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    $conversation = Conversation::create([
                        'user_id' => $user->id,
                        'admin_id' => $admin->id,
                    ]);
                }
            }
            
            if ($conversation) {
                $conversations = [$conversation];
            }
        }
        
        return view('chat.index', compact('conversations'));
    }
    
    /**
     * Get messages for a conversation.
     */
    public function getMessages(Request $request)
    {
        $user = Auth::user();
        $conversationId = $request->conversation_id;
        
        // Validate user has access to this conversation
        $conversation = Conversation::findOrFail($conversationId);
        
        if ($user->role !== 'admin' && $conversation->user_id !== $user->id) {
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
        $conversationId = $request->conversation_id;
        $message = $request->message;
        
        // Validate user has access to this conversation
        $conversation = Conversation::findOrFail($conversationId);
        
        if ($user->role !== 'admin' && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Determine receiver
        $receiverId = $user->role === 'admin' 
            ? $conversation->user_id 
            : $conversation->admin_id;
        
        // Create message
        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $message,
        ]);
        
        // Update conversation last message timestamp
        $conversation->update(['last_message_at' => now()]);
        
        // Broadcast the message
        broadcast(new MessageSent($chatMessage))->toOthers();
        
        return response()->json($chatMessage->load('sender'));
    }
}
