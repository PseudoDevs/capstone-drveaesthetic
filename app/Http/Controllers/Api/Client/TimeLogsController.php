<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\TimeLogs;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimeLogsController extends Controller
{
    public function index(): JsonResponse
    {
        $timeLogs = TimeLogs::with('user')->get();
        return response()->json([
            'success' => true,
            'data' => $timeLogs
        ]);
    }

    public function show($id): JsonResponse
    {
        $timeLog = TimeLogs::with('user')->find($id);
        
        if (!$timeLog) {
            return response()->json([
                'success' => false,
                'message' => 'Time log not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $timeLog
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'clock_in' => 'required|date',
            'clock_out' => 'nullable|date|after:clock_in',
            'date' => 'required|date',
        ]);

        $timeLog = TimeLogs::create($validated);
        
        if ($validated['clock_out'] ?? null) {
            $timeLog->calculateTotalHours();
        }

        return response()->json([
            'success' => true,
            'data' => $timeLog->load('user')
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $timeLog = TimeLogs::find($id);
        
        if (!$timeLog) {
            return response()->json([
                'success' => false,
                'message' => 'Time log not found'
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'clock_in' => 'sometimes|date',
            'clock_out' => 'nullable|date',
            'date' => 'sometimes|date',
        ]);

        $timeLog->update($validated);
        
        if ($timeLog->clock_in && $timeLog->clock_out) {
            $timeLog->calculateTotalHours();
        }

        return response()->json([
            'success' => true,
            'data' => $timeLog->load('user')
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $timeLog = TimeLogs::find($id);
        
        if (!$timeLog) {
            return response()->json([
                'success' => false,
                'message' => 'Time log not found'
            ], 404);
        }

        $timeLog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Time log deleted successfully'
        ]);
    }

    public function clockIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $existingLog = TimeLogs::getTodaysLog($validated['user_id']);
        
        if ($existingLog && $existingLog->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Already clocked in today'
            ], 400);
        }

        $timeLog = TimeLogs::create([
            'user_id' => $validated['user_id'],
            'clock_in' => now(),
            'date' => today(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $timeLog->load('user')
        ], 201);
    }

    public function clockOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $timeLog = TimeLogs::getTodaysLog($validated['user_id']);
        
        if (!$timeLog || !$timeLog->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'No active clock-in found for today'
            ], 400);
        }

        $timeLog->update(['clock_out' => now()]);
        $timeLog->calculateTotalHours();

        return response()->json([
            'success' => true,
            'data' => $timeLog->load('user')
        ]);
    }
}