<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('pages.contact');
    }
    
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Send email
        Mail::to('hello@example.com')->send(new ContactFormMail($validated));
        
        // Store in database if you want to keep records
        // ContactMessage::create($validated);
        
        return back()->with('success', 'Thank you for your message! I\'ll respond within 24-48 hours.');
    }
}
