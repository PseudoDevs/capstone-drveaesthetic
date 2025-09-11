<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Message;
use App\Models\Chat;

class ChatStreamController extends Controller
{
    public function stream(Request $request)
    {
        // Get user ID from request parameter
        $userId = $request->get('user_id');
        
        if (!$userId) {
            return response('User ID required', 400);
        }

        // Set up SSE headers
        $response = new Response();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Disable nginx buffering
        
        // Get the staff member
        $staff = \App\Models\User::where('role', 'Staff')->first();
        
        if (!$staff) {
            return response('No staff member found', 500);
        }

        // Find the appropriate chat for this user
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return response('User not found', 404);
        }
        
        if ($user->role === 'Staff') {
            // Staff member connecting - this shouldn't happen in normal flow
            // This occurs when staff initially loads before selecting a client
            $chat = Chat::where('staff_id', $staff->id)
                ->whereNotNull('last_message_at')
                ->orderBy('last_message_at', 'desc')
                ->first();
                
            if (!$chat) {
                // No active chats, create a placeholder or return error
                $chat = Chat::where('staff_id', $staff->id)->first();
                
                if (!$chat) {
                    return response('No chats found for staff member', 404);
                }
            }
        } else {
            // Client connecting OR staff connecting to specific client chat
            // Always find/create chat between staff and the specified user
            $chat = Chat::findOrCreateChat($staff->id, $userId);
        }
        
        if (!$chat) {
            return response('Chat could not be found or created', 500);
        }
        
        // Get the last message ID to track new messages
        $lastMessageId = $request->get('last_message_id', 0);
        
        return response()->stream(function() use ($chat, $lastMessageId, $userId) {
            // Keep track of the last message ID we've sent
            $currentLastId = $lastMessageId;
            
            // Send initial connection message
            echo "data: " . json_encode([
                'type' => 'connected',
                'chat_id' => $chat->id,
                'user_id' => $userId,
                'timestamp' => now()->toISOString()
            ]) . "\n\n";
            
            if (ob_get_level()) {
                ob_flush();
            }
            flush();
            
            // Loop to check for new messages
            while (true) {
                // Check for new messages
                $newMessages = Message::where('chat_id', $chat->id)
                    ->where('id', '>', $currentLastId)
                    ->with('user')
                    ->orderBy('created_at', 'asc')
                    ->get();
                
                foreach ($newMessages as $message) {
                    echo "data: " . json_encode([
                        'type' => 'message',
                        'message' => [
                            'id' => $message->id,
                            'chat_id' => $message->chat_id,
                            'user_id' => $message->user_id,
                            'message' => $message->message,
                            'created_at' => $message->created_at->toISOString(),
                            'user' => [
                                'id' => $message->user->id,
                                'name' => $message->user->name,
                                'role' => $message->user->role
                            ]
                        ]
                    ]) . "\n\n";
                    
                    $currentLastId = $message->id;
                    
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();
                }
                
                // Send heartbeat every 30 seconds
                echo "data: " . json_encode([
                    'type' => 'heartbeat',
                    'timestamp' => now()->toISOString()
                ]) . "\n\n";
                
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
                
                // Check if client disconnected
                if (connection_aborted()) {
                    break;
                }
                
                // Wait 2 seconds before checking again
                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}