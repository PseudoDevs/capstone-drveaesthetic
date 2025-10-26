<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class BillController extends Controller
{
    public function print(Bill $bill)
    {
        // Only staff, doctors, and admins can print bills
        $user = Auth::user();

        if (!in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            abort(403, 'Only authorized personnel can print bills.');
        }

        // Load the bill with related data
        $bill->load(['client', 'appointment.service', 'createdBy', 'payments']);

        // Generate HTML content
        $html = view('bills.print', compact('bill'))->render();

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

        return $dompdf->stream("bill_{$bill->bill_number}.pdf");
    }
}