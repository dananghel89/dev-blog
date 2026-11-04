<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

// Phase 6 of the Blogy theme plan moves these tests to Livewire::test() on the contact-form component.

test('the contact form validates its fields', function () {
    Mail::fake();

    $this->from(route('contact'))
        ->post(route('contact.send'), ['email' => 'not-an-email'])
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    Mail::assertNothingSent();
});

test('a valid contact form submission sends the contact mail', function () {
    Mail::fake();
    config(['project.contact_email' => 'inbox@example.com']);

    $this->from(route('contact'))
        ->post(route('contact.send'), [
            'name' => 'Jane Reader',
            'email' => 'jane@example.com',
            'subject' => 'Hello',
            'message' => "Line one\nLine two",
        ])
        ->assertRedirect(route('contact'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    Mail::assertSent(ContactFormMail::class, fn (ContactFormMail $mail) => $mail->hasTo('inbox@example.com')
        && $mail->data['email'] === 'jane@example.com');
});
