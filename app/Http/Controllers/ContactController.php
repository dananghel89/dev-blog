<?php

namespace App\Http\Controllers;

/*
 * The form itself is the contact-form Livewire component (resources/views/livewire/contact-form.blade.php).
 */
class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }
}
