<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedicalCertificateController extends Controller
{
    public function index(): JsonResponse
    {
        $certificates = MedicalCertificate::with(['staff', 'client'])->get();
        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    public function show($id): JsonResponse
    {
        $certificate = MedicalCertificate::with(['staff', 'client'])->find($id);
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Medical certificate not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $certificate
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'purpose' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'is_issued' => 'sometimes|boolean',
        ]);

        $certificate = MedicalCertificate::create($validated);

        return response()->json([
            'success' => true,
            'data' => $certificate->load(['staff', 'client'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $certificate = MedicalCertificate::find($id);
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Medical certificate not found'
            ], 404);
        }

        $validated = $request->validate([
            'staff_id' => 'sometimes|exists:users,id',
            'client_id' => 'sometimes|exists:users,id',
            'purpose' => 'sometimes|string',
            'amount' => 'sometimes|numeric|min:0',
            'is_issued' => 'sometimes|boolean',
        ]);

        $certificate->update($validated);

        return response()->json([
            'success' => true,
            'data' => $certificate->load(['staff', 'client'])
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $certificate = MedicalCertificate::find($id);
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Medical certificate not found'
            ], 404);
        }

        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medical certificate deleted successfully'
        ]);
    }
}