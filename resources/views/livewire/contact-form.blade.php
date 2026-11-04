<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

/*
 * Public contact form (Blogy contact.html markup). Sends ContactFormMail to config('project.contact_email').
 * Rate limit: MAX_ATTEMPTS valid messages per IP every DECAY_SECONDS.
 */
new class extends Component
{
    public const MAX_ATTEMPTS = 3;

    public const DECAY_SECONDS = 600;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:5000')]
    public string $message = '';

    public bool $sent = false;

    public function send(): void
    {
        $this->sent = false;
        $data = $this->validate();

        $sent = RateLimiter::attempt(
            'contact:'.request()->ip(),
            self::MAX_ATTEMPTS,
            fn () => Mail::to(config('project.contact_email'))->send(new ContactFormMail($data)),
            self::DECAY_SECONDS,
        );

        if (! $sent) {
            $this->addError('form', 'You have sent several messages already. Please try again in a few minutes.');

            return;
        }

        $this->reset('name', 'email', 'subject', 'message');
        $this->sent = true;
    }
};
?>

<form wire:submit="send" novalidate>
    @if ($sent)
        <div class="alert alert-success" role="status">Thank you for your message! I'll respond within 24-48 hours.</div>
    @endif
    @error('form')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="contact-name" class="visually-hidden">Your name</label>
            <input type="text" id="contact-name" wire:model="name" required
                   class="form-control @error('name') is-invalid @enderror" placeholder="Your Name">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="contact-email" class="visually-hidden">Your email</label>
            <input type="email" id="contact-email" wire:model="email" required
                   class="form-control @error('email') is-invalid @enderror" placeholder="Your Email">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 mb-3">
            <label for="contact-subject" class="visually-hidden">Subject</label>
            <input type="text" id="contact-subject" wire:model="subject" required
                   class="form-control @error('subject') is-invalid @enderror" placeholder="Subject">
            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 mb-3">
            <label for="contact-message" class="visually-hidden">Message</label>
            <textarea id="contact-message" wire:model="message" rows="7" required
                      class="form-control @error('message') is-invalid @enderror" placeholder="Message"></textarea>
            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="send">Send Message</span>
                <span wire:loading wire:target="send"><span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Sending...</span>
            </button>
        </div>
    </div>
</form>
