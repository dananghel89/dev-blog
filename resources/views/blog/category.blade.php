@extends('layouts.app')

@section('title', "Category: {$category->name} - Full Stack Development Blog")
@section('meta_description', "Posts about {$category->name} development - real challenges and solutions")
@section('meta_keywords', $category->name)

@section('content')
    <!-- Category Header -->
    <section class="category-header bg-black py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="badge mb-3 d-inline-block px-4 py-2" 
                          style="background-color: {{ $category->color }};
                                 color: {{ $category->name === 'JavaScript' ? '#000' : '#fff' }};
                                 font-size: 1.2rem;">
                        {{ $category->name }}
                    </span>
                    <h1 class="display-4 fw-bold mb-3">{{ $category->name }} Posts</h1>
                    @if($category->description)
                        <p class="lead text-secondary">{{ $category->description }}</p>
                    @endif
                    <p class="text-secondary">
                        <i class="bi bi-file-text me-2"></i>{{ $posts->total() }} posts found
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Posts List -->
    <section class="category-posts py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6">
                        <div class="card bg-dark border-secondary h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $post->formatted_date }}
                                    </small>
                                </div>
                                
                                <h3 class="h5 card-title">
                                    <a href="{{ route('blog.show', $post->slug) }}" 
                                       class="text-light text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                <p class="card-text text-secondary">
                                    {{ Str::limit($post->excerpt, 120) }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small">
                                        <i class="bi bi-clock me-1"></i>{{ $post->reading_time }}
                                    </span>
                                    <a href="{{ route('blog.show', $post->slug) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-folder-x display-1 text-secondary"></i>
                        <h3 class="mt-3">No posts in this category</h3>
                        <p class="text-secondary">Check back later for new content!</p>
                        <a href="{{ route('blog.index') }}" class="btn btn-primary mt-3">
                            View All Posts
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-5">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection