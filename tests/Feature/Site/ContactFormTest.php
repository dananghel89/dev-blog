<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

beforeEach(function () {
    Mail::fake();
    RateLimiter::clear('contact:127.0.0.1');
    config(['project.contact_email' => 'inbox@example.com']);
});

$valid = [
    'name' => 'Jane Reader',
    'email' => 'jane@example.com',
    'subject' => 'Hello',
    'message' => "Line one\nLine two",
];

test('the contact page renders the contact form component', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSeeLivewire('contact-form');
});

test('the contact form validates its fields', function () {
    Livewire::test('contact-form')
        ->set('email', 'not-an-email')
        ->call('send')
        ->assertHasErrors(['name', 'email', 'subject', 'message']);

    Mail::assertNothingSent();
});

test('a valid submission sends the contact mail and resets the form', function () use ($valid) {
    Livewire::test('contact-form')
        ->fill($valid)
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('sent', true)
        ->assertSet('name', '')
        ->assertSet('message', '')
        ->assertSee('Thank you for your message');

    Mail::assertSent(ContactFormMail::class, fn (ContactFormMail $mail) => $mail->hasTo('inbox@example.com')
        && $mail->data['email'] === 'jane@example.com');
});

test('the fourth message within ten minutes is rate limited', function () use ($valid) {
    $component = Livewire::test('contact-form');

    foreach (range(1, 3) as $i) {
        $component->fill($valid)->call('send')->assertHasNoErrors();
    }

    $component->fill($valid)->call('send')->assertHasErrors('form');

    Mail::assertSentCount(3);
});
