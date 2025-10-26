<?php

namespace App\Http\Controllers;

use App\Models\MedicalCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalCertificateController extends Controller
{
    public function print(MedicalCertificate $medicalCertificate)
    {
        // Only staff, doctors, and admins can print medical certificates
        $user = Auth::user();
        
        // Restrict printing to staff only
        if (!in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            abort(403, 'Only staff members can print medical certificates.');
        }

        // Load the medical certificate with related data
        $medicalCertificate->load(['client', 'appointment.service', 'staff']);

        return view('medical-certificates.print', compact('medicalCertificate'));
    }
}




