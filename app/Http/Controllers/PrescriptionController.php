<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function print(Prescription $prescription)
    {
        // Only staff, doctors, and admins can print prescriptions
        $user = Auth::user();
        
        // Restrict printing to staff only
        if (!in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            abort(403, 'Only staff members can print prescriptions.');
        }

        // Load the prescription with related data
        $prescription->load(['client', 'appointment.service', 'prescribedBy']);

        return view('prescriptions.print', compact('prescription'));
    }
}
