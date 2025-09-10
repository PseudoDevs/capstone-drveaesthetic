<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $chats = Chat::with(['staff', 'client', 'latestMessage'])
            ->where(function($query) use ($user) {
                $query->where('staff_id', $user->id)
                      ->orWhere('client_id', $user->id);
            })
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Extract users from chats that have conversations with current user
        $users = collect();
        foreach ($chats as $chat) {
            if ($chat->staff_id == $user->id) {
                $users->push($chat->client);
            } else {
                $users->push($chat->staff);
            }
        }

        // Remove duplicates
        $users = $users->unique('id')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'chats' => $chats,
                'users_with_conversations' => $users,
                'current_user' => $user
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $chat = Chat::with(['staff', 'client', 'messages.user'])->find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chat
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::findOrCreateChat($validated['staff_id'], $validated['client_id']);

        return response()->json([
            'success' => true,
            'data' => $chat->load(['staff', 'client'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        $validated = $request->validate([
            'last_message_at' => 'sometimes|date',
        ]);

        $chat->update($validated);

        return response()->json([
            'success' => true,
            'data' => $chat->load(['staff', 'client'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        $chat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat deleted successfully'
        ]);
    }

    public function searchStaff(Request $request): JsonResponse
    {
        // Support both Bearer token (mobile/API) and web session authentication
        $user = auth('sanctum')->user() ?? auth('web')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $query = $request->get('query', '');

        // For clients, only show the single staff member (and doctor if needed)
        // For staff/doctors/admins, show all other roles
        if ($user->role === 'Client') {
            $staffQuery = \App\Models\User::whereIn('role', ['Staff'])
                ->where('id', '!=', $user->id); // Exclude current user
        } else {
            $staffQuery = \App\Models\User::whereIn('role', ['Staff'])
                ->where('id', '!=', $user->id); // Exclude current user
        }

        // Apply search filter if query is provided (case-insensitive search)
        if (!empty(trim($query))) {
            $searchTerm = trim($query);
            $staffQuery->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE LOWER(?)', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(email) LIKE LOWER(?)', ["%{$searchTerm}%"]);
            });
        }

        $staff = $staffQuery
            ->select('id', 'name', 'email', 'role', 'avatar')
            ->orderBy('name')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $staff
        ]);
    }

    public function getMessages($userId): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $chat = Chat::findOrCreateChat($user->id, $userId);
        $messages = $chat->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'chat_id' => $chat->id,
                'messages' => $messages
            ]
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::findOrCreateChat($user->id, $validated['receiver_id']);

        $message = \App\Models\Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $chat->update(['last_message_at' => now()]);

        // Broadcast the message for real-time updates
        broadcast(new MessageSent($message));

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $message->load('user'),
                'chat_id' => $chat->id
            ]
        ], 201);
    }

    // Mobile-specific endpoints for React Native
    public function getConversations(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $chats = Chat::with(['staff', 'client', 'latestMessage.user'])
            ->where(function($query) use ($user) {
                $query->where('staff_id', $user->id)
                      ->orWhere('client_id', $user->id);
            })
            ->whereHas('messages') // Only show chats with messages
            ->orderBy('last_message_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        // Format conversations for mobile
        $formattedChats = $chats->getCollection()->map(function($chat) use ($user) {
            $otherUser = $chat->staff_id == $user->id ? $chat->client : $chat->staff;
            $latestMessage = $chat->latestMessage;

            return [
                'id' => $chat->id,
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                    'avatar' => $otherUser->avatar,
                    'role' => $otherUser->role,
                ],
                'latest_message' => $latestMessage ? [
                    'id' => $latestMessage->id,
                    'message' => $latestMessage->message,
                    'sender_id' => $latestMessage->user_id,
                    'sender_name' => $latestMessage->user->name,
                    'is_mine' => $latestMessage->user_id == $user->id,
                    'is_read' => $latestMessage->is_read,
                    'created_at' => $latestMessage->created_at->toISOString(),
                    'time_ago' => $latestMessage->created_at->diffForHumans(),
                ] : null,
                'unread_count' => $chat->messages()
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count(),
                'last_activity' => $chat->last_message_at ? $chat->last_message_at->toISOString() : null,
                'created_at' => $chat->created_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'conversations' => $formattedChats,
                'pagination' => [
                    'current_page' => $chats->currentPage(),
                    'last_page' => $chats->lastPage(),
                    'per_page' => $chats->perPage(),
                    'total' => $chats->total(),
                    'has_more' => $chats->hasMorePages(),
                ]
            ]
        ]);
    }

    public function getConversationMessages(Request $request, $chatId): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

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

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);

        $messages = $chat->messages()
            ->with('user:id,name,avatar')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        // Format messages for mobile
        $formattedMessages = $messages->getCollection()->reverse()->values()->map(function($message) use ($user) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->user_id,
                'sender' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar' => $message->user->avatar,
                ],
                'is_mine' => $message->user_id == $user->id,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toISOString(),
                'time_ago' => $message->created_at->diffForHumans(),
            ];
        });

        // Mark messages as read
        $chat->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => [
                'messages' => $formattedMessages,
                'chat_info' => [
                    'id' => $chat->id,
                    'other_user' => $chat->staff_id == $user->id ? [
                        'id' => $chat->client->id,
                        'name' => $chat->client->name,
                        'avatar' => $chat->client->avatar,
                        'role' => $chat->client->role,
                    ] : [
                        'id' => $chat->staff->id,
                        'name' => $chat->staff->name,
                        'avatar' => $chat->staff->avatar,
                        'role' => $chat->staff->role,
                    ],
                ],
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'has_more' => $messages->hasMorePages(),
                ]
            ]
        ]);
    }

    public function sendMessageMobile(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $validated = $request->validate([
            'chat_id' => 'nullable|exists:chats,id',
            'receiver_id' => 'required_without:chat_id|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        // Get or create chat
        if (isset($validated['chat_id'])) {
            $chat = Chat::find($validated['chat_id']);
            if (!$chat || ($chat->staff_id != $user->id && $chat->client_id != $user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid chat'
                ], 400);
            }
        } else {
            $chat = Chat::findOrCreateChat($user->id, $validated['receiver_id']);
        }

        $message = \App\Models\Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $chat->update(['last_message_at' => now()]);

        // Broadcast the message for real-time updates
        broadcast(new MessageSent($message));

        // Format response for mobile
        return response()->json([
            'success' => true,
            'data' => [
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->user_id,
                    'sender' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                    ],
                    'is_mine' => true,
                    'is_read' => false,
                    'created_at' => $message->created_at->toISOString(),
                    'time_ago' => $message->created_at->diffForHumans(),
                ],
                'chat_id' => $chat->id
            ]
        ], 201);
    }

    public function markMessagesAsRead(Request $request, $chatId): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

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

        // Mark all messages from other user as read
        $updated = $chat->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => [
                'marked_read_count' => $updated
            ]
        ]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $totalUnread = \App\Models\Message::whereHas('chat', function($query) use ($user) {
            $query->where(function($q) use ($user) {
                $q->where('staff_id', $user->id)
                  ->orWhere('client_id', $user->id);
            });
        })
        ->where('user_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_unread' => $totalUnread
            ]
        ]);
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $query = $request->get('query', '');
        $userRole = $user->role;

        // Determine which roles the current user can chat with
        $searchRoles = [];
        if ($userRole === 'Client') {
            $searchRoles = ['Staff', 'Doctor']; // Clients can only chat with Staff and Doctor
        } else {
            $searchRoles = ['Client', 'Staff', 'Doctor', 'Admin'];
        }

        $users = \App\Models\User::whereIn('role', $searchRoles)
            ->where('id', '!=', $user->id)
            ->where(function($q) use ($query) {
                if ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                }
            })
            ->select('id', 'name', 'email', 'role', 'avatar')
            ->limit(20)
            ->get()
            ->map(function($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'avatar' => $u->avatar,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function updateTypingStatus(Request $request, $chatId): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $validated = $request->validate([
            'is_typing' => 'required|boolean',
        ]);

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

        // Broadcast typing status
        broadcast(new \Illuminate\Broadcasting\PrivateChannel('chat.' . $chatId))
            ->with([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'is_typing' => $validated['is_typing'],
                'timestamp' => now()->toISOString(),
            ])
            ->as('user.typing');

        return response()->json([
            'success' => true,
            'message' => 'Typing status updated'
        ]);
    }

    public function deleteMessage(Request $request, $messageId): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $message = \App\Models\Message::with('chat')->find($messageId);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        // Verify user owns this message
        if ($message->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own messages'
            ], 403);
        }

        // Check if user has access to this chat
        $chat = $message->chat;
        if ($chat->staff_id != $user->id && $chat->client_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Soft delete the message by updating its content
        $message->update([
            'message' => '[Message deleted]',
        ]);

        // Broadcast message deletion
        broadcast(new \Illuminate\Broadcasting\PrivateChannel('chat.' . $chat->id))
            ->with([
                'message_id' => $message->id,
                'deleted_by' => $user->id,
                'timestamp' => now()->toISOString(),
            ])
            ->as('message.deleted');

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}
