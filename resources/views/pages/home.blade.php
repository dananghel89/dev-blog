@extends('layouts.app')

@section('title', 'Home - Full Stack Development Blog')
@section('meta_description', 'Daily development challenges and solutions in PHP, MySQL, HTML, CSS, and JavaScript')
@section('meta_keywords', 'PHP, MySQL, HTML, CSS, JavaScript, Laravel, web development, coding challenges')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section bg-black py-5">
        <div class="container">
            <div class="row align-items-center min-vh-50 py-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold mb-3">
                        Hi, I'm <span class="text-primary">Your Name</span> 👋
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Documenting daily battles and victories in PHP, MySQL, HTML, CSS & JavaScript
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('blog.index') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-journal-bookmark-fill me-2"></i>Read the Blog
                        </a>
                        <a href="{{ route('blog.show', $featuredPosts->first()?->slug ?? '#') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-lightning-charge-fill me-2"></i>Latest Challenge
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts Section -->
    <section class="featured-posts py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold">
                        <i class="bi bi-stars text-primary me-2"></i>
                        Latest Development Battles
                    </h2>
                    <p class="text-secondary">Real problems, real solutions from my daily coding</p>
                </div>
            </div>
            
            <div class="row g-4">
                @forelse($featuredPosts as $post)
                    <div class="col-md-4">
                        <div class="card bg-dark border-secondary h-100 hover-lift">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge" style="background-color: {{ $post->category?->color ?? '#6366f1' }};">
                                        {{ $post->category?->name ?? 'Uncategorized' }}
                                    </span>
                                    <small class="text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $post->formatted_date }}
                                    </small>
                                </div>
                                
                                <h5 class="card-title">
                                    <a href="{{ $post->url }}" class="text-light text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                
                                <p class="card-text text-secondary">{{ $post->excerpt }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-secondary">
                                        <i class="bi bi-clock me-1"></i> {{ $post->reading_time }}
                                    </span>
                                    <a href="{{ $post->url }}" class="btn btn-outline-primary btn-sm">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-journal-x display-1 text-secondary"></i>
                        <p class="lead text-secondary mt-3">No posts yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">
                    View All Posts <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why This Blog Section -->
    <section class="why-blog bg-black py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3">
                        <i class="bi bi-question-circle-fill text-primary me-2"></i>
                        Why I Write This Blog
                    </h2>
                    <p class="lead text-secondary">
                        Every day as a full-stack developer brings new challenges—from stubborn MySQL queries 
                        to CSS layout mysteries. This blog is my digital notebook where I document real problems 
                        I've encountered and how I solved them.
                    </p>
                    <p class="text-secondary">
                        Whether you're a fellow developer or just curious about coding, you'll find practical 
                        solutions here. No theory without practice - every post includes the exact problem, 
                        debugging process, and working solution.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('about') }}" class="btn btn-link text-primary p-0">
                            Learn more about me <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card bg-dark border-secondary text-center p-4">
                                <i class="bi bi-filetype-php display-4 text-primary"></i>
                                <h5 class="mt-2">PHP</h5>
                                <span class="badge bg-secondary">{{ $categories['PHP'] ?? 0 }} posts</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-dark border-secondary text-center p-4">
                                <i class="bi bi-database display-4 text-primary"></i>
                                <h5 class="mt-2">MySQL</h5>
                                <span class="badge bg-secondary">{{ $categories['MySQL'] ?? 0 }} posts</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-dark border-secondary text-center p-4">
                                <i class="bi bi-filetype-html display-4 text-primary"></i>
                                <h5 class="mt-2">HTML/CSS</h5>
                                <span class="badge bg-secondary">{{ $categories['HTML/CSS'] ?? 0 }} posts</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-dark border-secondary text-center p-4">
                                <i class="bi bi-filetype-js display-4 text-primary"></i>
                                <h5 class="mt-2">JavaScript</h5>
                                <span class="badge bg-secondary">{{ $categories['JavaScript'] ?? 0 }} posts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Quick View -->
    <section class="categories py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold">
                        <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                        Explore by Technology
                    </h2>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($categories as $name => $count)
                    <div class="col-md-3 col-6">
                        <a href="{{ route('blog.category', Str::slug($name)) }}" class="text-decoration-none">
                            <div class="card bg-dark border-secondary text-center p-4 hover-scale">
                                @php
                                    $icon = match(true) {
                                        str_contains($name, 'PHP') => 'bi-filetype-php',
                                        str_contains($name, 'MySQL') => 'bi-database',
                                        str_contains($name, 'HTML') => 'bi-filetype-html',
                                        str_contains($name, 'CSS') => 'bi-filetype-css',
                                        str_contains($name, 'JavaScript') => 'bi-filetype-js',
                                        default => 'bi-code-square'
                                    };
                                @endphp
                                <i class="{{ $icon }} display-4 text-primary"></i>
                                <h5 class="mt-2 text-light">{{ $name }}</h5>
                                <span class="badge bg-secondary">{{ $count }} posts</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter bg-black py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <i class="bi bi-envelope-paper-fill display-4 text-primary mb-3"></i>
                    <h3 class="fw-bold mb-3">Stay Updated</h3>
                    <p class="text-secondary mb-4">
                        Get the latest development challenges and solutions delivered to your inbox.
                    </p>
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control form-control-lg bg-dark text-light border-secondary" 
                                   placeholder="Your email address" required>
                            <button class="btn btn-primary px-4" type="submit">
                                Subscribe
                            </button>
                        </div>
                        <small class="text-secondary">
                            <i class="bi bi-shield-check me-1"></i>
                            No spam, unsubscribe anytime.
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    .min-vh-50 {
        min-height: 50vh;
    }
    
    .hero-section {
        background: linear-gradient(180deg, #0a0c0f 0%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .card {
        transition: all 0.3s ease;
        border-width: 1px;
    }
    
    .card:hover {
        border-color: #6366f1;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .newsletter-form .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
    // Newsletter form submission handling
    document.querySelector('.newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const email = form.querySelector('input[type="email"]').value;
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Subscribing...';
        
        // Simulate form submission (replace with actual AJAX call)
        setTimeout(() => {
            button.innerHTML = '<i class="bi bi-check-lg me-2"></i>Subscribed!';
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
                form.reset();
            }, 3000);
        }, 1500);
    });
</script>
@endpush