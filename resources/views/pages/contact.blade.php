@extends('layouts.app')

@section('title', 'Contact Me - Get in Touch')
@section('meta_description', 'Have a question or want to collaborate? Send me a message and I\'ll respond within 24-48 hours.')
@section('meta_keywords', 'contact, get in touch, collaboration, developer contact')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-black py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-envelope-paper-fill text-primary me-3"></i>
                        Get in Touch
                    </h1>
                    <p class="lead text-secondary">
                        Have a question about a post? Want to collaborate? Just want to say hi? 
                        I'd love to hear from you!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form Column -->
                <div class="col-lg-7">
                    <div class="card bg-dark border-secondary">
                        <div class="card-body p-4 p-lg-5">
                            <h3 class="fw-bold mb-4 text-white">
                                <i class="bi bi-send text-primary me-2"></i>
                                Send Me a Message
                            </h3>
                            
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                                @csrf
                                
                                <!-- Name Field -->
                                <div class="mb-4">
                                    <label for="name" class="form-label text-white">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        Your Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control bg-secondary-subtle border-secondary @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           placeholder="John Doe"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label for="email" class="form-label text-white">
                                        <i class="bi bi-envelope me-2 text-primary"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control bg-secondary-subtle border-secondary @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="john@example.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Subject Field -->
                                <div class="mb-4">
                                    <label for="subject" class="form-label text-white">
                                        <i class="bi bi-chat-dots me-2 text-primary"></i>
                                        Subject <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control bg-secondary-subtle border-secondary @error('subject') is-invalid @enderror" 
                                           id="subject" 
                                           name="subject" 
                                           value="{{ old('subject') }}"
                                           placeholder="What's this about?"
                                           required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Message Field -->
                                <div class="mb-4">
                                    <label for="message" class="form-label text-white">
                                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                                        Your Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control bg-secondary-subtle border-secondary @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="6"
                                              placeholder="Write your message here..."
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-send me-2"></i>
                                    Send Message
                                </button>
                                
                                <!-- Response Time Note -->
                                <p class="text-secondary text-center mt-3 small">
                                    <i class="bi bi-clock-history me-1"></i>
                                    I typically respond within 24-48 hours
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Info Column -->
                <div class="col-lg-5">
                    <!-- Quick Contact Info -->
                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 text-white">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                Quick Info
                            </h4>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-envelope-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-white">Email</h6>
                                    <a href="mailto:hello@example.com" class="text-secondary text-decoration-none">
                                        hello@example.com
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-github text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-white">GitHub</h6>
                                    <a href="#" class="text-secondary text-decoration-none">
                                        github.com/example
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-twitter-x text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-white">Twitter/X</h6>
                                    <a href="#" class="text-secondary text-decoration-none">
                                        @example
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="contact-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-linkedin text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-white">LinkedIn</h6>
                                    <a href="#" class="text-secondary text-decoration-none">
                                        linkedin.com/in/example
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collaboration Card -->
                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3 text-white">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                Let's Collaborate
                            </h4>
                            <p class="text-secondary mb-3">
                                Looking for a guest post? Want me to review your code? 
                                Open to paid writing? Let's discuss!
                            </p>
                            <ul class="list-unstyled text-white">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 small"></i>
                                    Guest blogging
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 small"></i>
                                    Code reviews
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 small"></i>
                                    Technical writing
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 small"></i>
                                    Open source contributions
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Response Time Card -->
                    <div class="card bg-dark border-secondary">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-stopwatch-fill text-primary fs-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold mb-1 text-white">Quick Response</h5>
                                    <p class="text-secondary mb-0 small">
                                        I aim to respond to all messages within 24-48 hours. 
                                        For urgent matters, reach out on Twitter/X.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Optional) -->
    <section class="faq-section bg-black py-5 mt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="fw-bold">
                        <i class="bi bi-question-circle-fill text-primary me-2"></i>
                        Frequently Asked Questions
                    </h2>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-white">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                How fast do you respond?
                            </h5>
                            <p class="card-text text-secondary">
                                I typically respond within 24-48 hours. If you haven't heard back in 3 days, 
                                feel free to send a follow-up!
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-white">
                                <i class="bi bi-journal-code text-primary me-2"></i>
                                Can I suggest a blog topic?
                            </h5>
                            <p class="card-text text-secondary">
                                Absolutely! I'm always looking for new ideas. If there's a development challenge 
                                you'd like me to cover, just let me know.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-white">
                                <i class="bi bi-people text-primary me-2"></i>
                                Do you accept guest posts?
                            </h5>
                            <p class="card-text text-secondary">
                                Yes! I'm open to guest contributions. Send me your idea and we can discuss.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-white">
                                <i class="bi bi-bug text-primary me-2"></i>
                                Found a bug in my code?
                            </h5>
                            <p class="card-text text-secondary">
                                Please let me know! I appreciate corrections and improvements. 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .contact-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    
    .contact-icon:hover {
        transform: scale(1.1);
    }
    
    .contact-form .form-control {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contact-form .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        background-color: #000000;
    }
    
    .contact-form .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .contact-form .form-control.is-invalid:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    .btn-primary {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
    }
    
    .card {
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    
    .card:hover {
        border-color: #6366f1;
        transform: translateY(-5px);
    }
    
    /* Light mode adjustments */
    body.light-mode .contact-form .form-control:focus {
        background-color: #ffffff;
    }
    
    body.light-mode .bg-opacity-10 {
        background-color: rgba(99, 102, 241, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Optional: Add any custom JavaScript for the contact form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.contact-form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
                
                // Re-enable after 10 seconds in case something goes wrong
                setTimeout(function() {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 10000);
            });
        }
    });
</script>
@endpush