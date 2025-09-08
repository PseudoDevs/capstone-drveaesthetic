<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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
        // Support both API authentication and web authentication
        $user = auth()->user() ?? auth('web')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $query = $request->get('query', '');

        $staff = \App\Models\User::whereIn('role', ['Staff'])
            ->where('id', '!=', $user->id) // Exclude current user
            ->where(function($q) use ($query) {
                if ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                }
            })
            ->select('id', 'name', 'email', 'role', 'avatar')
            ->limit(20)
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

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $message->load('user'),
                'chat_id' => $chat->id
            ]
        ], 201);
    }
}
