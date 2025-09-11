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
        // Get the single staff member and all clients
        $staff = \App\Models\User::where('role', 'Staff')->first();
        $clients = \App\Models\User::where('role', 'Client')->get();

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'No staff member found'
            ], 404);
        }

        // Get all chats involving the staff member
        $chats = Chat::with(['staff', 'client', 'latestMessage'])
            ->where('staff_id', $staff->id)
            ->orderBy('last_message_at', 'desc')
            ->get();

        // For each client, ensure they have a potential chat entry (even if no messages yet)
        $chatUsers = collect();
        foreach ($clients as $client) {
            $existingChat = $chats->where('client_id', $client->id)->first();
            if ($existingChat) {
                $chatUsers->push([
                    'user' => $client,
                    'chat' => $existingChat,
                    'has_messages' => $existingChat->latestMessage !== null
                ]);
            } else {
                $chatUsers->push([
                    'user' => $client,
                    'chat' => null,
                    'has_messages' => false
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'chats' => $chats,
                'staff' => $staff,
                'clients' => $clients,
                'chat_users' => $chatUsers
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


    public function getMessages($userId): JsonResponse
    {
        // Get the single staff member
        $staff = \App\Models\User::where('role', 'Staff')->first();
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'No staff member found'
            ], 404);
        }

        // Find or create chat between staff and the specified user
        $chat = Chat::findOrCreateChat($staff->id, $userId);
        $messages = $chat->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'chat_id' => $chat->id,
                'messages' => $messages,
                'staff' => $staff,
                'other_user' => \App\Models\User::find($userId)
            ]
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        // Get the staff member
        $staff = \App\Models\User::where('role', 'Staff')->first();
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'No staff member found'
            ], 404);
        }

        // Ensure one of the participants is the staff member
        if ($validated['sender_id'] != $staff->id && $validated['receiver_id'] != $staff->id) {
            return response()->json([
                'success' => false,
                'message' => 'All messages must involve the staff member'
            ], 400);
        }

        $chat = Chat::findOrCreateChat($validated['sender_id'], $validated['receiver_id']);

        $message = \App\Models\Message::create([
            'chat_id' => $chat->id,
            'user_id' => $validated['sender_id'],
            'message' => $validated['message'],
        ]);

        $chat->update(['last_message_at' => now()]);

        // Note: Real-time updates now handled by Server-Sent Events

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $message->load('user'),
                'chat_id' => $chat->id
            ]
        ], 201);
    }

}
