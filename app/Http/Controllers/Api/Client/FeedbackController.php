<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{
    public function index(): JsonResponse
    {
        $feedback = Feedback::with(['client', 'appointment'])->get();
        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }

    public function show($id): JsonResponse
    {
        $feedback = Feedback::with(['client', 'appointment'])->find($id);
        
        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'success' => true,
            'data' => $feedback->load(['client', 'appointment'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ], 404);
        }

        $validated = $request->validate([
            'client_id' => 'sometimes|exists:users,id',
            'appointment_id' => 'sometimes|exists:appointments,id',
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $feedback->update($validated);

        return response()->json([
            'success' => true,
            'data' => $feedback->load(['client', 'appointment'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback not found'
            ], 404);
        }

        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully'
        ]);
    }
}