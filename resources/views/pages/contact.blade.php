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
                        @include('partials.social-links', ['wrapper' => 'mt-4', 'class' => 'list-unstyled social social-inline'])
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                    <livewire:contact-form />
                </div>
            </div>
        </div>
    </div>
@endsection
