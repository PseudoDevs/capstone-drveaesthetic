<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = Message::with(['chat', 'user'])->get();
        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function show($id): JsonResponse
    {
        $message = Message::with(['chat', 'user'])->find($id);
        
        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'is_read' => 'sometimes|boolean',
        ]);

        $message = Message::create($validated);

        return response()->json([
            'success' => true,
            'data' => $message->load(['chat', 'user'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $message = Message::find($id);
        
        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        $validated = $request->validate([
            'message' => 'sometimes|string',
            'is_read' => 'sometimes|boolean',
        ]);

        $message->update($validated);

        return response()->json([
            'success' => true,
            'data' => $message->load(['chat', 'user'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $message = Message::find($id);
        
        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}