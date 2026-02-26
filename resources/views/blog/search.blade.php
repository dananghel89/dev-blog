@extends('layouts.app')

@section('title', "Search Results: {$query} - Full Stack Development Blog")
@section('meta_description', "Search results for '{$query}'")

@section('content')
    <!-- Search Header -->
    <section class="search-header bg-black py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-search text-primary me-3"></i>
                        Search Results
                    </h1>
                    <p class="lead text-secondary">
                        @if($posts->total() > 0)
                            Found {{ $posts->total() }} posts matching "{{ $query }}"
                        @else
                            No posts found for "{{ $query }}"
                        @endif
                    </p>
                    
                    <!-- Search Again -->
                    <form action="{{ route('blog.search') }}" method="GET" class="mt-4">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" 
                                           name="q" 
                                           class="form-control form-control-lg bg-dark text-light border-secondary" 
                                           placeholder="Try another search..."
                                           value="{{ $query }}">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    @if($posts->count() > 0)
        <section class="search-results py-5">
            <div class="container">
                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card bg-dark border-secondary h-100 hover-lift">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        @if($post->category)
                                            <span class="badge" 
                                                  style="background-color: {{ $post->category->color }};
                                                         color: {{ $post->category->name === 'JavaScript' ? '#000' : '#fff' }};">
                                                {{ $post->category->name }}
                                            </span>
                                        @endif
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
                                    
                                    @if($post->tags->count() > 0)
                                        <div class="mt-3 pt-3 border-top border-secondary">
                                            @foreach($post->tags as $tag)
                                                <span class="badge bg-secondary me-1">
                                                    #{{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="mt-5">
                        {{ $posts->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection