@extends('layouts.app')

@section('title', 'Full Stack Development Blog')
@section('meta_description', 'Daily development challenges and solutions in PHP, MySQL, HTML, CSS, and JavaScript. Real problems, real solutions.')
@section('meta_keywords', 'PHP, MySQL, HTML, CSS, JavaScript, Laravel, web development, coding challenges')

@section('content')

    @if ($featuredPosts->isEmpty())
        @include('partials.page-hero', ['title' => config('app.name'), 'subtitle' => 'No posts yet. Check back soon.'])
    @else
        {{-- Featured grid (Blogy "retro layout"): the 5 newest posts. --}}
        @php
            $featured = fn (int $index) => $featuredPosts->get($index);
        @endphp
        <section class="section bg-light">
            <div class="container">
                <h1 class="visually-hidden">{{ config('app.name') }}</h1>
                <div class="row align-items-stretch retro-layout">
                    <div class="col-md-4">
                        @foreach ([0, 1] as $index)
                            @if ($post = $featured($index))
                                <a href="{{ $post->url }}" @class(['h-entry v-height gradient', 'mb-30' => $index === 0])>
                                    <div class="featured-img" style="background-image: url('{{ $post->imageUrl('horizontal') }}');" role="img" aria-label="{{ $post->title }}"></div>
                                    <div class="text">
                                        <span class="date">{{ $post->published_at?->format('M. jS, Y') }}</span>
                                        <h2>{{ $post->title }}</h2>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-md-4">
                        @if ($post = $featured(2))
                            <a href="{{ $post->url }}" class="h-entry img-5 h-100 gradient">
                                <div class="featured-img" style="background-image: url('{{ $post->imageUrl('vertical') }}');" role="img" aria-label="{{ $post->title }}"></div>
                                <div class="text">
                                    <span class="date">{{ $post->published_at?->format('M. jS, Y') }}</span>
                                    <h2>{{ $post->title }}</h2>
                                </div>
                            </a>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @foreach ([3, 4] as $index)
                            @if ($post = $featured($index))
                                <a href="{{ $post->url }}" @class(['h-entry v-height gradient', 'mb-30' => $index === 3])>
                                    <div class="featured-img" style="background-image: url('{{ $post->imageUrl('horizontal') }}');" role="img" aria-label="{{ $post->title }}"></div>
                                    <div class="text">
                                        <span class="date">{{ $post->published_at?->format('M. jS, Y') }}</span>
                                        <h2>{{ $post->title }}</h2>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- One block per top category: 2 cards + 3 short entries. --}}
        @foreach ($categoryBlocks as $category)
            <section class="section posts-entry">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h2 class="posts-entry-title">{{ $category->name }}</h2>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <a href="{{ $category->url }}" class="read-more">View All</a>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-9">
                            <div class="row g-3">
                                @foreach ($category->posts->take(2) as $post)
                                    <div class="col-md-6">
                                        @include('blog.partials.post-card', ['post' => $post, 'ratio' => 'square'])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled blog-entry-sm">
                                @foreach ($category->posts->slice(2) as $post)
                                    <li>
                                        <span class="date">{{ $post->published_at?->format('M. jS, Y') }}</span>
                                        <h3><a href="{{ $post->url }}">{{ $post->title }}</a></h3>
                                        <p>{{ Str::limit(strip_tags($post->excerpt), 90) }}</p>
                                        <p><a href="{{ $post->url }}" class="read-more">Continue Reading</a></p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach

        {{-- Small grid: the next 4 posts. --}}
        @if ($gridPosts->isNotEmpty())
            <section class="section posts-entry posts-entry-sm bg-light">
                <div class="container">
                    <div class="row">
                        @foreach ($gridPosts as $post)
                            <div class="col-md-6 col-lg-3">
                                @include('blog.partials.post-card', ['post' => $post, 'excerptLength' => 80, 'button' => false])
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- More posts (Blogy "post-entry-alt"). --}}
        @if ($morePosts->isNotEmpty())
            <section class="section">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h2 class="posts-entry-title">More Posts</h2>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <a href="{{ route('blog.index') }}" class="read-more">View All</a>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($morePosts as $post)
                            <div class="col-lg-4 mb-4">
                                <div class="post-entry-alt">
                                    <a href="{{ $post->url }}" class="img-link">
                                        <img src="{{ $post->imageUrl('horizontal') }}" alt="{{ $post->title }}" class="img-fluid img-cover img-cover-horizontal" loading="lazy">
                                    </a>
                                    <div class="excerpt">
                                        <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
                                        <div class="post-meta align-items-center text-left clearfix">
                                            <span>{{ $post->published_at?->format('F j, Y') }} &nbsp;-&nbsp; {{ $post->reading_time }}</span>
                                        </div>
                                        <p>{{ Str::limit(strip_tags($post->excerpt), 140) }}</p>
                                        <p><a href="{{ $post->url }}" class="read-more">Continue Reading</a></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

@endsection
