<?php

namespace App\Http\Controllers\Admin;

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
            
        return view('admin.messenger.index', compact('conversations'));
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
}