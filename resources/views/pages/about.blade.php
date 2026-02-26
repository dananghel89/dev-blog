@extends('layouts.app')

@section('title', 'About Me - Full Stack Developer')
@section('meta_description', 'Learn about the full-stack developer behind this blog, with experience in PHP, MySQL, HTML, CSS, and JavaScript.')
@section('meta_keywords', 'about, full stack developer, PHP developer, MySQL expert, web developer bio')

@section('content')
    <!-- Hero Section -->
    <section class="about-hero bg-black py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="mb-4">
                        <img src="https://placehold.co/150x150/6366f1/ffffff?text=dev-blog" 
                             alt="Profile" 
                             class="rounded-circle border border-3 border-primary"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h1 class="display-4 fw-bold mb-3">
                        Hi, I'm <span class="text-primary">Your Name</span>
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Full-Stack Developer passionate about solving real-world problems through code
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('blog.index') }}" class="btn btn-primary px-4">
                            <i class="bi bi-journal-bookmark-fill me-2"></i>Read My Blog
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-envelope me-2"></i>Get in Touch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bio Section -->
    <section class="bio py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-4">
                        <i class="bi bi-person-vcard-fill text-primary me-2"></i>
                        About Me
                    </h2>
                    <div class="card bg-dark border-secondary p-4">
                        <p class="lead mb-4">
                            I'm a full-stack developer with {{ $stats['years_experience'] }}+ years of experience 
                            building web applications. I specialize in PHP, MySQL, and modern JavaScript, 
                            and I love sharing the challenges I encounter in my daily work.
                        </p>
                        <p class="text-secondary">
                            This blog started as my personal development diary but evolved into a resource 
                            for developers facing similar challenges. Every post is born from a real problem 
                            I encountered during work - no theory without practice!
                        </p>
                        
                        <!-- Stats Row -->
                        <div class="row g-4 mt-3 text-center">
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="display-4 text-primary fw-bold">{{ $stats['years_experience'] }}</div>
                                    <div class="text-secondary">Years Experience</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="display-4 text-primary fw-bold">{{ $stats['posts_written'] }}</div>
                                    <div class="text-secondary">Blog Posts</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="display-4 text-primary fw-bold">{{ $stats['technologies'] }}</div>
                                    <div class="text-secondary">Technologies</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="display-4 text-primary fw-bold">{{ $stats['countries_reached'] }}</div>
                                    <div class="text-secondary">Countries Reached</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="tech-stack bg-black py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold">
                        <i class="bi bi-code-square text-primary me-2"></i>
                        My Tech Stack
                    </h2>
                    <p class="text-secondary">Technologies I use daily to solve problems</p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($techStack as $category => $items)
                    <div class="col-md-6 col-lg-3">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    @switch($category)
                                        @case('PHP')
                                            <i class="bi bi-filetype-php me-2"></i>
                                            @break
                                        @case('MySQL')
                                            <i class="bi bi-database me-2"></i>
                                            @break
                                        @case('Frontend')
                                            <i class="bi bi-brush me-2"></i>
                                            @break
                                        @case('Tools')
                                            <i class="bi bi-tools me-2"></i>
                                            @break
                                        @default
                                            <i class="bi bi-code me-2"></i>
                                    @endswitch
                                    {{ $category }}
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($items as $item)
                                        <span class="badge bg-secondary">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Work Philosophy -->
    <section class="philosophy py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-4 text-center">
                        <i class="bi bi-lightbulb text-primary me-2"></i>
                        My Development Philosophy
                    </h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card bg-dark border-secondary h-100">
                                <div class="card-body">
                                    <div class="text-primary fs-1 mb-3">
                                        <i class="bi bi-bug"></i>
                                    </div>
                                    <h5>Real Problems, Real Solutions</h5>
                                    <p class="text-secondary">
                                        Every blog post documents an actual challenge I faced, 
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
                    <p class="text-secondary">
        <!-- Connect Section -->
    <section class="connect bg-black py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">
                        <i class="bi bi-chat-dots-fill text-primary me-2"></i>
                        Let's Connect
                    </h2>
                    <p class="lead text-secondary mb-4">
                        I'm always happy to discuss code, debug together, or just chat about development.
                    </p>
                    
                    <div class="d-flex gap-4 justify-content-center">
                        <a href="#" class="text-secondary fs-1 hover-text-primary">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" class="text-secondary fs-1 hover-text-primary">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="text-secondary fs-1 hover-text-primary">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="text-secondary fs-1 hover-text-primary">
                            <i class="bi bi-stack-overflow"></i>
                        </a>
                        <a href="#" class="text-secondary fs-1 hover-text-primary">
                            <i class="bi bi-envelope-fill"></i>
                        </a>
                    </div>
                    
                    <div class="mt-5">
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-send me-2"></i>Send Me a Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(180deg, #0a0c0f 0%, #000000 100%);
        position: relative;
        overflow: hidden;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .stat-card {
        padding: 1rem;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .hover-text-primary {
        transition: color 0.3s ease, transform 0.3s ease;
        display: inline-block;
    }
    
    .hover-text-primary:hover {
        color: #6366f1 !important;
        transform: translateY(-3px);
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        /* background-color: #2d2d2d !important; */
    }
    
    .card {
        transition: all 0.3s ease;
        border-width: 1px;
    }
    
    .card:hover {
        border-color: #6366f1;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);
    }
    
    /* Light mode adjustments */
    /* body.light-mode .badge {
        background-color: #e9ecef !important;
        color: #212529 !important;
    } */
    
    body.light-mode .about-hero::before {
        background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
    }
</style>
@endpush