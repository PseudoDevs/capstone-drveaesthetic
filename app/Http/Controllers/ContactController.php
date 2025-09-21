<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone-no' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Normalize the phone field name
        $validated['phone_no'] = $validated['phone-no'];
        unset($validated['phone-no']);

        ContactSubmission::create($validated);

        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
