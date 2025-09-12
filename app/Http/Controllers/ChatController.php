<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Services\AutoIntroMessageService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // For testing purposes, allow user_id parameter
        $userId = $request->get('user_id');
        
        if ($userId) {
            $currentUser = \App\Models\User::find($userId);
        } else {
            $currentUser = auth()->user();
        }
        
        if (!$currentUser) {
            // Default to a test user for demo
            $currentUser = \App\Models\User::where('role', 'Client')->first();
        }
        
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

        // Format messages for frontend
        $formattedMessages = $messages->map(function($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'user_id' => $message->user_id,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar' => $message->user->avatar,
                ],
                'created_at' => $message->created_at->toISOString(),
                'chat_id' => $message->chat_id,
            ];
        });

        return response()->json([
            'chat_id' => $chat->id,
            'messages' => $formattedMessages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
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
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'user_id' => $message->user_id,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                ],
                'created_at' => $message->created_at->toISOString(),
                'chat_id' => $message->chat_id,
            ]
        ]);
    }

    public function pollNewMessages(Request $request, $chatId)
    {
        $user = auth()->user();
        $lastMessageId = $request->get('last_message_id', 0);

        $chat = Chat::find($chatId);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        // Verify user has access to this chat
        if ($chat->staff_id != $user->id && $chat->client_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Get new messages since last poll
        $newMessages = Message::where('chat_id', $chat->id)
            ->where('id', '>', $lastMessageId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Format messages for frontend
        $formattedMessages = $newMessages->map(function($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'user_id' => $message->user_id,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar' => $message->user->avatar,
                ],
                'created_at' => $message->created_at->toISOString(),
                'chat_id' => $message->chat_id,
            ];
        });

        // Mark new messages from other user as read
        if ($newMessages->count() > 0) {
            $chat->messages()
                ->whereIn('id', $newMessages->pluck('id'))
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'messages' => $formattedMessages,
            'has_new_messages' => $newMessages->count() > 0,
            'last_message_id' => $newMessages->count() > 0 ? $newMessages->last()->id : $lastMessageId,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function pollConversationUpdates(Request $request)
    {
        $user = auth()->user();
        $lastMessageId = $request->get('last_message_id', 0);

        // Get all chats where user is participant
        $chatIds = Chat::where(function($query) use ($user) {
            $query->where('staff_id', $user->id)
                  ->orWhere('client_id', $user->id);
        })->pluck('id');

        // Check for new messages in any of user's chats
        $newMessages = Message::whereIn('chat_id', $chatIds)
            ->where('id', '>', $lastMessageId)
            ->with(['user', 'chat', 'chat.staff', 'chat.client'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Group messages by chat and format for frontend
        $conversationUpdates = [];
        foreach ($newMessages as $message) {
            $chat = $message->chat;
            $otherUser = $chat->staff_id == $user->id ? $chat->client : $chat->staff;
            
            $conversationUpdates[] = [
                'chat_id' => $chat->id,
                'user' => $otherUser,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'created_at' => $message->created_at->toISOString(),
                ],
                'unread_count' => $chat->messages()
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count(),
                'last_message_at' => $chat->last_message_at
            ];
        }

        return response()->json([
            'success' => true,
            'conversation_updates' => $conversationUpdates,
            'has_updates' => count($conversationUpdates) > 0,
            'last_message_id' => $newMessages->count() > 0 ? $newMessages->last()->id : $lastMessageId,
            'timestamp' => now()->toISOString()
        ]);
    }
}