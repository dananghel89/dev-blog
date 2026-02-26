<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends \App\Http\Controllers\Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        // Here you would typically:
        // 1. Save email to database
        // 2. Send confirmation email
        // 3. Integrate with newsletter service (Mailchimp, etc.)

        // For now, just return success message
        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    public function confirm($token)
    {
        // Handle email confirmation
        return redirect()->route('home')->with('success', 'Email confirmed successfully!');
    }

    public function unsubscribe(Request $request)
    {
        // Handle unsubscribe
        return redirect()->route('home')->with('success', 'You have been unsubscribed.');
    }
}
