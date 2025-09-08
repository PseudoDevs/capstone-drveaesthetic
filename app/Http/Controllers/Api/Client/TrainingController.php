<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TrainingController extends Controller
{
    public function index(): JsonResponse
    {
        $trainings = Training::all();
        return response()->json([
            'success' => true,
            'data' => $trainings
        ]);
    }

    public function show($id): JsonResponse
    {
        $training = Training::find($id);
        
        if (!$training) {
            return response()->json([
                'success' => false,
                'message' => 'Training not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $training
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'thumbnail' => 'nullable|string',
            'description' => 'required|string',
            'is_published' => 'sometimes|boolean',
        ]);

        $training = Training::create($validated);

        return response()->json([
            'success' => true,
            'data' => $training
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $training = Training::find($id);
        
        if (!$training) {
            return response()->json([
                'success' => false,
                'message' => 'Training not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|string',
            'thumbnail' => 'nullable|string',
            'description' => 'sometimes|string',
            'is_published' => 'sometimes|boolean',
        ]);

        $training->update($validated);

        return response()->json([
            'success' => true,
            'data' => $training
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $training = Training::find($id);
        
        if (!$training) {
            return response()->json([
                'success' => false,
                'message' => 'Training not found'
            ], 404);
        }

        $training->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training deleted successfully'
        ]);
    }

    public function published(): JsonResponse
    {
        $trainings = Training::where('is_published', true)->get();
        return response()->json([
            'success' => true,
            'data' => $trainings
        ]);
    }
}