@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->excerpt ?? '')
@section('meta_keywords', $post->tags->pluck('name')->implode(', '))
@section('og_image', $post->hasImage() ? $post->imageUrl() : asset('images/default-og.png'))

@section('content')
    @php($shareUrl = urlencode($post->url))

    {{-- Cover: the featured image, or the theme's primary colour when the post has none. --}}
    <div @class(['site-cover site-cover-sm same-height overlay single-page', 'bg-primary' => ! $post->hasImage()])
         @if ($post->hasImage()) style="background-image: url('{{ $post->imageUrl('horizontal') }}');" @endif>
        <div class="container">
            <div class="row same-height justify-content-center">
                <div class="col-md-8">
                    <div class="post-entry text-center">
                        <h1 class="mb-4">{{ $post->title }}</h1>
                        <div class="post-meta align-items-center text-center">
                            <span>{{ $post->published_at?->format('F j, Y') }}</span>
                            <span>&nbsp;-&nbsp; {{ $post->reading_time }}</span>
                            @if ($post->category)
                                <span>&nbsp;-&nbsp; <a href="{{ $post->category->url }}" class="text-white">{{ $post->category->name }}</a></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="row blog-entries element-animate">
                <div class="col-md-12 col-lg-8 main-content">

                    {{-- Seeded HTML for now; the finish plan switches posts to Markdown with raw HTML stripped. --}}
                    <div class="post-content-body">
                        {!! $post->content !!}
                    </div>

                    <div class="pt-5">
                        <p>
                            @if ($post->category)
                                Category: <a href="{{ $post->category->url }}">{{ $post->category->name }}</a>
                            @endif
                            @if ($post->tags->isNotEmpty())
                                &nbsp; Tags:
                                @foreach ($post->tags as $tag)
                                    <a href="{{ $tag->url }}">#{{ $tag->name }}</a>@if (! $loop->last), @endif
                                @endforeach
                            @endif
                        </p>
                    </div>

                    <div class="pt-3 post-share">
                        <span class="me-2">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($post->title) }}"
                           target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary me-1" aria-label="Share on X">
                            <span class="bi bi-twitter-x"></span>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                           target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary me-1" aria-label="Share on LinkedIn">
                            <span class="bi bi-linkedin"></span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                           target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" aria-label="Share on Facebook">
                            <span class="bi bi-facebook"></span>
                        </a>
                    </div>

                </div>

                @include('blog.partials.sidebar')
            </div>
        </div>
    </section>

    @if ($relatedPosts->isNotEmpty())
        <section class="section posts-entry posts-entry-sm bg-light">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-uppercase text-black">More {{ $post->category?->name }} Posts</div>
                </div>
                <div class="row">
                    @foreach ($relatedPosts as $related)
                        <div class="col-md-6 col-lg-3">
                            @include('blog.partials.post-card', ['post' => $related, 'excerptLength' => 80, 'button' => false])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
