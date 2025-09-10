<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        $chats = Chat::with(['staff', 'client', 'latestMessage'])
            ->where(function($query) use ($currentUser) {
                $query->where('staff_id', $currentUser->id)
                      ->orWhere('client_id', $currentUser->id);
            })
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Extract users from chats to display in sidebar
        $users = collect();
        foreach ($chats as $chat) {
            if ($chat->staff_id == $currentUser->id) {
                $users->push($chat->client);
            } else {
                $users->push($chat->staff);
            }
        }
        
        // Remove duplicates
        $users = $users->unique('id');

        // For clients, always provide the staff member data
        $staffMember = null;
        if ($currentUser->role === 'Client') {
            $staffMember = \App\Models\User::where('role', 'Staff')->first();
        }

        return view('chat.index', compact('chats', 'users', 'currentUser', 'staffMember'));
    }

    public function getConversations()
    {
        $currentUser = auth()->user();
        $chats = Chat::with(['staff', 'client', 'latestMessage.user'])
            ->where(function($query) use ($currentUser) {
                $query->where('staff_id', $currentUser->id)
                      ->orWhere('client_id', $currentUser->id);
            })
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Build conversation data with user info and latest message
        $conversations = collect();
        foreach ($chats as $chat) {
            $otherUser = $chat->staff_id == $currentUser->id ? $chat->client : $chat->staff;
            
            $conversationData = [
                'user' => $otherUser,
                'chat_id' => $chat->id,
                'last_message_at' => $chat->last_message_at,
                'latest_message' => $chat->latestMessage ? [
                    'id' => $chat->latestMessage->id,
                    'message' => $chat->latestMessage->message,
                    'user_id' => $chat->latestMessage->user_id,
                    'user_name' => $chat->latestMessage->user->name,
                    'created_at' => $chat->latestMessage->created_at,
                    'is_own_message' => $chat->latestMessage->user_id == $currentUser->id
                ] : null
            ];
            
            $conversations->push($conversationData);
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    public function getMessages($userId)
    {
        $user = auth()->user();
        $chat = Chat::findOrCreateChat($user->id, $userId);
        $messages = $chat->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'chat_id' => $chat->id,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $chat = Chat::findOrCreateChat($user->id, $validated['receiver_id']);

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $chat->update(['last_message_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => $message->load('user')
        ]);
    }
}