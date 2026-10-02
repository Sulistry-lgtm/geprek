<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageMail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Send email to the support address
        Mail::to('geprekbestie76@gmail.com')->send(new ContactMessageMail(
            $request->name,
            $request->email,
            $request->message
        ));

        return back()->with('success', 'Pesan Anda telah berhasil dikirim ke email support kami!');
    }
}
