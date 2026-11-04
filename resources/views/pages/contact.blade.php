@extends('layouts.app')

@section('title', 'Contact Me - Get in Touch')
@section('meta_description', 'Have a question or want to collaborate? Send me a message and I\'ll respond within 24-48 hours.')
@section('meta_keywords', 'contact, get in touch, collaboration, developer contact')

@section('content')
    @include('partials.page-hero', [
        'title' => 'Contact',
        'subtitle' => 'Have a question about a post? Want to collaborate? Just want to say hi?',
    ])

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info">
                        <div class="email mt-2">
                            <i class="bi bi-envelope" aria-hidden="true"></i>
                            <h4 class="mb-2">Email:</h4>
                            <p><a href="mailto:{{ config('project.contact_email') }}">{{ config('project.contact_email') }}</a></p>
                        </div>
                        <div class="open-hours mt-4">
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            <h4 class="mb-2">Response time:</h4>
                            <p>Usually within 24-48 hours</p>
                        </div>
                    </div>
                </div>

                {{-- Plain POST for now; becomes the contact-form Livewire component in Phase 6. --}}
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                    <form action="{{ route('contact.send') }}" method="POST" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="visually-hidden">Your name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="form-control @error('name') is-invalid @enderror" placeholder="Your Name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="visually-hidden">Your email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="form-control @error('email') is-invalid @enderror" placeholder="Your Email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="subject" class="visually-hidden">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                       class="form-control @error('subject') is-invalid @enderror" placeholder="Subject">
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="message" class="visually-hidden">Message</label>
                                <textarea id="message" name="message" rows="7" required
                                          class="form-control @error('message') is-invalid @enderror" placeholder="Message">{{ old('message') }}</textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
