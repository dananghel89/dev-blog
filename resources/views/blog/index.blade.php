@extends('layouts.app')

@section('title', 'Blog - Full Stack Development Challenges')
@section('meta_description', 'Explore real-world development challenges and solutions in PHP, MySQL, HTML, CSS, and JavaScript.')
@section('meta_keywords', 'blog, PHP blog, MySQL tips, web development, coding challenges')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-black py-4">
        <div class="container">
            <h1 class="display-4 fw-bold">
                <i class="bi bi-journal-bookmark-fill text-primary me-3"></i>
                The Dev Journal
            </h1>
            <p class="lead text-secondary">
                Real problems, real solutions from my daily coding battles.
            </p>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="search-section py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form action="{{ route('blog.search') }}" method="GET" class="d-flex">
                        <input type="text" 
                               name="q" 
                               class="form-control form-control-lg bg-dark text-light border-secondary" 
                               placeholder="Search blog posts..." 
                               value="{{ request('q') }}">
                        <button type="submit" class="btn btn-primary ms-2 px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="blog-content py-4">
        <div class="container">
            <div class="row">
                <!-- Blog Posts Column -->
                <div class="col-lg-8">
                    @if($posts->count() > 0)
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-md-6">
                                    <div class="card bg-dark border-secondary h-100 hover-lift">
                                        @if($post->featured_image)
                                            <img src="{{ $post->featured_image }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $post->title }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge" 
                                                      style="background-color: {{ $post->category?->color ?? '#6366f1' }};
                                                             color: {{ $post->category?->name === 'JavaScript' ? '#000' : '#fff' }};">
                                                    {{ $post->category?->name ?? 'Uncategorized' }}
                                                </span>
                                                <small class="text-secondary">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $post->formatted_date }}
                                                </small>
                                            </div>
                                            
                                            <h5 class="card-title">
                                                <a href="{{ route('blog.show', $post->slug) }}" 
                                                   class="text-light text-decoration-none">
                                                    {{ $post->title }}
                                                </a>
                                            </h5>
                                            
                                            <p class="card-text text-secondary">
                                                {{ Str::limit($post->excerpt, 100) }}
                                            </p>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clock text-secondary me-1"></i>
                                                    <span class="text-secondary small">{{ $post->reading_time }}</span>
                                                </div>
                                                <a href="{{ route('blog.show', $post->slug) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    Read More <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                            
                                            @if($post->tags->count() > 0)
                                                <div class="mt-3 pt-3 border-top border-secondary">
                                                    @foreach($post->tags as $tag)
                                                        <a href="{{ route('blog.tag', $tag->slug) }}" 
                                                           class="badge bg-secondary text-decoration-none me-1 mb-2">
                                                            #{{ $tag->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-5">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x display-1 text-secondary"></i>
                            <h3 class="mt-3">No posts found</h3>
                            <p class="text-secondary">Check back later for new content!</p>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">
                        <!-- Stats Card -->
                        <div class="card bg-dark border-secondary mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                                    Blog Stats
                                </h5>
                                <ul class="list-unstyled text-white">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary">Total posts:</span>
                                        <span class="fw-bold">{{ $posts->total() }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary">Categories:</span>
                                        <span class="fw-bold">{{ $categories->count() }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-secondary">Tags:</span>
                                        <span class="fw-bold">{{ $popularTags->count() }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="card bg-dark border-secondary mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                                    Categories
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($categories as $category)
                                        <a href="{{ route('blog.category', $category->slug) }}" 
                                           class="badge text-decoration-none px-3 py-2"
                                           style="background-color: {{ $category->color }};
                                                  color: {{ $category->name === 'JavaScript' ? '#000' : '#fff' }};">
                                            {{ $category->name }} ({{ $category->posts_count }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Popular Tags -->
                        <div class="card bg-dark border-secondary">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="bi bi-tags-fill text-primary me-2"></i>
                                    Popular Tags
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($popularTags as $tag)
                                        <a href="{{ route('blog.tag', $tag->slug) }}" 
                                           class="badge bg-secondary text-decoration-none">
                                            #{{ $tag->name }} ({{ $tag->posts_count }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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
    
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: opacity 0.3s ease;
    }
    
    .badge:hover {
        opacity: 0.8;
    }
    
    .pagination {
        gap: 0.5rem;
    }
    
    .page-link {
        background-color: #1a1a1a;
        border-color: #2d2d2d;
        color: #e4e6eb;
    }
    
    .page-link:hover {
        background-color: #2d2d2d;
        border-color: #6366f1;
        color: #6366f1;
    }
    
    .page-item.active .page-link {
        background-color: #6366f1;
        border-color: #6366f1;
    }
</style>
@endpush