<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function showMedicalForm(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }

        // Check if form is already completed
        if ($appointment->form_completed) {
            return redirect()->route('users.dashboard')->with('info', 'This form has already been completed.');
        }

        // Check if appointment is eligible for form completion
        if (!in_array($appointment->status, ['PENDING', 'SCHEDULED'])) {
            return redirect()->route('users.dashboard')->with('error', 'This appointment is not eligible for form completion.');
        }

        return view('forms.medical-form', compact('appointment'));
    }

    public function completeForm(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }

        // Check if form is already completed
        if ($appointment->form_completed) {
            return redirect()->route('users.dashboard')->with('info', 'This form has already been completed.');
        }

        // Validate the form data
        $request->validate([
            'medical_form_data.patient_name' => 'required|string|max:255',
            'medical_form_data.date' => 'required|date',
            'medical_form_data.address' => 'required|string',
            'medical_form_data.procedure' => 'required|string|max:255',
            'medical_form_data.signature_date' => 'required|date',
            'medical_form_data.signature_location' => 'required|string',
            'medical_form_data.signature_data' => 'required|string',
        ]);

        // Update the appointment with form data
        $appointment->update([
            'medical_form_data' => $request->input('medical_form_data'),
            'form_completed' => true,
            'form_completed_at' => now(),
        ]);

        return redirect()->route('users.dashboard')->with('success', 
            'Medical form completed successfully! Your appointment is now ready for confirmation.'
        );
    }

    public function viewCompletedForm(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }

        // Check if form is completed
        if (!$appointment->form_completed) {
            return redirect()->route('users.dashboard')->with('error', 'This form has not been completed yet.');
        }

        return view('forms.view-medical-form', compact('appointment'));
    }
}
