<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class PaymentController extends Controller
{
    public function print(Payment $payment)
    {
        // Only staff, doctors, and admins can print payment receipts
        $user = Auth::user();

        if (!in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            abort(403, 'Only authorized personnel can print payment receipts.');
        }

        // Load the payment with related data
        $payment->load(['bill.appointment.service', 'client', 'receivedBy']);

        // Generate HTML content
        $html = view('payments.print', compact('payment'))->render();

        // Create PDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("payment_receipt_{$payment->payment_number}.pdf");
    }
}