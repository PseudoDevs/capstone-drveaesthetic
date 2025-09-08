<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\ClinicService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClinicServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = ClinicService::with(['category', 'staff'])->get();
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function show($id): JsonResponse
    {
        $service = ClinicService::with(['category', 'staff'])->find($id);
        
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'staff_id' => 'required|exists:users,id',
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|string',
            'duration' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $service = ClinicService::create($validated);

        return response()->json([
            'success' => true,
            'data' => $service->load(['category', 'staff'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $service = ClinicService::find($id);
        
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'staff_id' => 'sometimes|exists:users,id',
            'service_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'thumbnail' => 'nullable|string',
            'duration' => 'sometimes|integer',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'data' => $service->load(['category', 'staff'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $service = ClinicService::find($id);
        
        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}