<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Get user's prescriptions
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 20);
            $page = $request->get('page', 1);

            $prescriptions = Prescription::where('client_id', $user->id)
                ->with(['appointment.service', 'prescribedBy'])
                ->orderBy('prescribed_date', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'prescriptions' => $prescriptions->items(),
                    'pagination' => [
                        'current_page' => $prescriptions->currentPage(),
                        'last_page' => $prescriptions->lastPage(),
                        'per_page' => $prescriptions->perPage(),
                        'total' => $prescriptions->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching prescriptions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific prescription details
     */
    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $prescription = Prescription::where('id', $id)
                ->where('client_id', $user->id)
                ->with(['appointment.service', 'prescribedBy'])
                ->first();

            if (!$prescription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription not found or access denied'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'prescription' => [
                        'id' => $prescription->id,
                        'prescription_number' => $prescription->prescription_number,
                        'client_name' => $prescription->client_name,
                        'client_age' => $prescription->client_age,
                        'client_gender' => $prescription->client_gender,
                        'service_name' => $prescription->appointment->service->service_name ?? 'N/A',
                        'prescribed_by' => $prescription->prescribedBy->name ?? 'N/A',
                        'prescribed_date' => $prescription->prescribed_date,
                        'medications' => $prescription->medications,
                        'instructions' => $prescription->instructions,
                        'notes' => $prescription->notes,
                        'status' => $prescription->status,
                        'created_at' => $prescription->created_at,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching prescription details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download prescription as PDF
     */
    public function download($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $prescription = Prescription::where('id', $id)
                ->where('client_id', $user->id)
                ->with(['appointment.service', 'prescribedBy'])
                ->first();

            if (!$prescription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription not found or access denied'
                ], 404);
            }

            // Generate PDF URL (you can implement PDF generation here)
            $pdfUrl = route('prescription.download', $prescription->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'download_url' => $pdfUrl,
                    'prescription' => [
                        'id' => $prescription->id,
                        'prescription_number' => $prescription->prescription_number,
                        'client_name' => $prescription->client_name,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating prescription download',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get prescription statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $user = Auth::user();

            $totalPrescriptions = Prescription::where('client_id', $user->id)->count();
            $activePrescriptions = Prescription::where('client_id', $user->id)
                ->where('status', 'active')
                ->count();
            $completedPrescriptions = Prescription::where('client_id', $user->id)
                ->where('status', 'completed')
                ->count();

            // Get recent prescriptions (last 5)
            $recentPrescriptions = Prescription::where('client_id', $user->id)
                ->with(['appointment.service', 'prescribedBy'])
                ->orderBy('prescribed_date', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'total_prescriptions' => $totalPrescriptions,
                        'active_prescriptions' => $activePrescriptions,
                        'completed_prescriptions' => $completedPrescriptions,
                    ],
                    'recent_prescriptions' => $recentPrescriptions->map(function($prescription) {
                        return [
                            'id' => $prescription->id,
                            'prescription_number' => $prescription->prescription_number,
                            'service_name' => $prescription->appointment->service->service_name ?? 'N/A',
                            'prescribed_by' => $prescription->prescribedBy->name ?? 'N/A',
                            'prescribed_date' => $prescription->prescribed_date,
                            'status' => $prescription->status,
                        ];
                    }),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching prescription statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

