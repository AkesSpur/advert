<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display the user's chat interface
     */
    public function index()
    {
        // If user is admin, redirect to admin messenger
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.messenger.index');
        }
        
        // Regular users can only see their conversation with admin
        $conversation = Conversation::firstOrCreate(
            ['user_id' => Auth::id()],
            ['admin_id' => User::where('role', 'admin')->first()->id ?? null, 'last_message_at' => now()]
        );
        
        // Mark all unread messages as read
        ChatMessage::where('conversation_id', $conversation->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Create an array with the single conversation for consistent view handling
        $conversations = collect([$conversation]);
                    
        return view('chat.index', compact('conversation', 'conversations'));
        

    }
    
    /**
     * Get messages for a specific conversation
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id'
        ]);
        
        $conversation = Conversation::findOrFail($request->conversation_id);
        
        // Check if user has access to this conversation
        if (!Auth::user()->isAdmin() && $conversation->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $messages = $conversation->messages()->with(['sender'])->get();
        
        // Mark messages as read
        $conversation->messages()
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json($messages);
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string'
        ]);
        
        $conversation = Conversation::findOrFail($request->conversation_id);
        
        // Check if user has access to this conversation
        if (!Auth::user()->isAdmin() && $conversation->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Determine recipient
        $recipientId = Auth::user()->isAdmin() 
            ? $conversation->user_id 
            : ($conversation->admin_id ?? User::where('role', 'admin')->first()->id);
            
        // Create message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $recipientId,
            'message' => $request->message
        ]);
        
        // Broadcast the message
        broadcast(new MessageSent($message->load('sender')))->toOthers();
        
        // Update conversation last_message_at
        $conversation->update(['last_message_at' => now()]);
        
        // If admin_id is null, assign the first admin
        if (is_null($conversation->admin_id) && Auth::user()->isAdmin()) {
            $conversation->update(['admin_id' => Auth::id()]);
        }
        
        // Broadcast the message
        // broadcast(new MessageSent($message))->toOthers();
        
        // Return the message with sender information
        return response()->json($message->load('sender'));
    }
}
