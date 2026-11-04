@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->excerpt ?? '')
@section('meta_keywords', $post->tags->pluck('name')->implode(', '))
@section('og_image', $post->hasImage() ? $post->imageUrl() : asset('images/default-og.png'))

@section('content')
    <!-- Post Header -->
    <section class="post-header bg-black py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    @if($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}" 
                           class="badge mb-3 d-inline-block"
                           style="background-color: {{ $post->category->color }};
                                  color: {{ $post->category->name === 'JavaScript' ? '#000' : '#fff' }};
                                  padding: 0.75rem 1.5rem;">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    
                    <h1 class="display-4 fw-bold mb-4">{{ $post->title }}</h1>
                    
                    <div class="d-flex justify-content-center align-items-center text-secondary">
                        <span>
                            <i class="bi bi-calendar3 me-2"></i>
                            {{ $post->formatted_date }}
                        </span>
                        <span class="mx-3">•</span>
                        <span>
                            <i class="bi bi-clock me-2"></i>
                            {{ $post->reading_time }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if($post->featured_image)
        <section class="featured-image py-4">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ $post->featured_image }}" 
                             alt="{{ $post->title }}" 
                             class="img-fluid rounded-4 w-100"
                             style="max-height: 500px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Post Content -->
    <section class="post-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <article class="content-body">
                        {!! $post->content !!}
                    </article>
                    
                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                        <div class="tags-section mt-5 pt-4 border-top border-secondary">
                            <h5 class="mb-3">
                                <i class="bi bi-tags-fill text-primary me-2"></i>
                                Tags:
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" 
                                       class="badge bg-secondary text-decoration-none px-3 py-2">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Share Buttons -->
                    <div class="share-section mt-5">
                        <h5 class="mb-3">Share this post:</h5>
                        <div class="d-flex gap-2">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                               target="_blank"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-twitter-x me-2"></i>Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}" 
                               target="_blank"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-linkedin me-2"></i>LinkedIn
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" 
                               target="_blank"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-facebook me-2"></i>Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
        <section class="related-posts bg-black py-5">
            <div class="container">
                <h2 class="fw-bold mb-4">
                    <i class="bi bi-link-45deg text-primary me-2"></i>
                    Related Posts
                </h2>
                
                <div class="row g-4">
                    @foreach($relatedPosts as $related)
                        <div class="col-md-4">
                            <div class="card bg-dark border-secondary h-100 hover-lift">
                                @if($related->featured_image)
                                    <img src="{{ $related->featured_image }}" 
                                         class="card-img-top" 
                                         alt="{{ $related->title }}"
                                         style="height: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('blog.show', $related->slug) }}" 
                                           class="text-light text-decoration-none">
                                            {{ $related->title }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-secondary small">
                                        {{ Str::limit($related->excerpt, 80) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Comments Section (Placeholder) -->
    <section class="comments py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h3 class="mb-4">
                        <i class="bi bi-chat-dots-fill text-primary me-2"></i>
                        Comments
                    </h3>
                    
                    <div class="card bg-dark border-secondary p-4 text-center">
                        <i class="bi bi-chat display-4 text-secondary mb-3"></i>
                        <h5>Comments Coming Soon!</h5>
                        <p class="text-secondary mb-0">
                            The comment system is currently under development.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .content-body {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .content-body h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        color: #6366f1;
    }
    
    .content-body h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .content-body p {
        margin-bottom: 1.5rem;
    }
    
    .content-body pre {
        background-color: #1a1a1a;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        overflow-x: auto;
    }
    
    .content-body code {
        font-family: 'Fira Code', monospace;
        font-size: 0.9rem;
    }
    
    .content-body ul, .content-body ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .content-body li {
        margin-bottom: 0.5rem;
    }
    
    .content-body blockquote {
        border-left: 4px solid #6366f1;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #9aa0a6;
    }
    
    body.light-mode .content-body pre {
        background-color: #f6f8fa;
        border: 1px solid #dee2e6;
    }
    
    .hover-lift {
        transition: transform 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
    }
</style>
@endpush