<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Services\PushNotificationService;
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
                    'has_messages' => $existingChat->latestMessage !== null,
                    'last_message_at' => $existingChat->last_message_at
                ]);
            } else {
                $chatUsers->push([
                    'user' => $client,
                    'chat' => null,
                    'has_messages' => false,
                    'last_message_at' => null
                ]);
            }
        }

        // Sort chat_users by last_message_at (latest first), putting chats with messages at the top
        $chatUsers = $chatUsers->sortByDesc(function ($chatUser) {
            // Prioritize chats with messages, then sort by last_message_at
            if ($chatUser['has_messages'] && $chatUser['last_message_at']) {
                return $chatUser['last_message_at'];
            }
            // Chats without messages go to the bottom
            return '1900-01-01 00:00:00'; // Very old date to ensure they appear last
        });

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

        // Get both sender and receiver users
        $sender = \App\Models\User::find($validated['sender_id']);
        $receiver = \App\Models\User::find($validated['receiver_id']);
        
        // Get the staff member
        $staff = \App\Models\User::where('role', 'Staff')->first();
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'No staff member found'
            ], 404);
        }

        // Debug logging
        \Log::info('ChatController sendMessage debug', [
            'staff_id' => $staff->id,
            'staff_name' => $staff->name,
            'sender_id' => $validated['sender_id'],
            'sender_name' => $sender->name,
            'sender_role' => $sender->role,
            'receiver_id' => $validated['receiver_id'],
            'receiver_name' => $receiver->name,
            'receiver_role' => $receiver->role,
            'sender_is_staff' => $validated['sender_id'] == $staff->id,
            'receiver_is_staff' => $validated['receiver_id'] == $staff->id
        ]);

        // Modified validation: Allow messages between staff and clients, or between staff members
        $senderIsStaffOrDoctor = in_array($sender->role, ['Staff', 'Doctor', 'Admin']);
        $receiverIsStaffOrDoctor = in_array($receiver->role, ['Staff', 'Doctor', 'Admin']);
        $involvesSingleStaffMember = ($validated['sender_id'] == $staff->id || $validated['receiver_id'] == $staff->id);
        
        if (!$involvesSingleStaffMember && !($senderIsStaffOrDoctor || $receiverIsStaffOrDoctor)) {
            return response()->json([
                'success' => false,
                'message' => 'Messages must involve the staff member or authorized personnel',
                'debug' => [
                    'staff_id' => $staff->id,
                    'sender_id' => $validated['sender_id'],
                    'receiver_id' => $validated['receiver_id'],
                    'sender_role' => $sender->role,
                    'receiver_role' => $receiver->role
                ]
            ], 400);
        }

        $chat = Chat::findOrCreateChat($validated['sender_id'], $validated['receiver_id']);

        $message = \App\Models\Message::create([
            'chat_id' => $chat->id,
            'user_id' => $validated['sender_id'],
            'message' => $validated['message'],
        ]);

        // Send push notification to the recipient
        $this->sendNotificationToRecipient($chat, $sender, $validated['message']);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $message->load('user'),
                'chat_id' => $chat->id
            ]
        ], 201);
    }

    /**
     * Send push notification to the recipient when a new message is sent
     */
    private function sendNotificationToRecipient(Chat $chat, User $sender, string $message): void
    {
        try {
            // Determine who the recipient is (the other user in the chat)
            $recipient = null;
            if ($chat->client_id === $sender->id) {
                $recipient = $chat->staff; // Client sent message, notify staff
            } else {
                $recipient = $chat->client; // Staff sent message, notify client
            }

            // Only send notification if recipient has FCM token and is not the sender
            if ($recipient && $recipient->fcm_token && $recipient->id !== $sender->id) {
                $pushService = new PushNotificationService();

                // Send push notification
                $pushService->sendChatNotification(
                    $recipient,
                    $sender,
                    $message,
                    $chat->id
                );

                \Log::info('Chat push notification sent', [
                    'chat_id' => $chat->id,
                    'sender_id' => $sender->id,
                    'recipient_id' => $recipient->id,
                    'message_preview' => substr($message, 0, 50)
                ]);
            } else {
                \Log::info('Chat push notification not sent', [
                    'chat_id' => $chat->id,
                    'sender_id' => $sender->id,
                    'recipient_id' => $recipient?->id,
                    'has_fcm_token' => $recipient?->fcm_token ? true : false,
                    'reason' => !$recipient ? 'No recipient found' : (!$recipient->fcm_token ? 'No FCM token' : 'Unknown')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send chat push notification', [
                'chat_id' => $chat->id,
                'sender_id' => $sender->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
