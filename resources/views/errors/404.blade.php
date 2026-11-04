@extends('layouts.app')

@section('title', 'Page not found')

@push('styles')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.page-hero', [
        'title' => 'Page not found',
        'subtitle' => 'The page you are looking for doesn\'t exist or was moved.',
    ])

    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="search-form-wrap mb-5">
                        <form action="{{ route('blog.search') }}" method="GET" class="sidebar-search-form" role="search">
                            <span class="bi-search" aria-hidden="true"></span>
                            <input type="search" name="q" class="form-control" placeholder="Search the blog" aria-label="Search posts" minlength="2" required>
                        </form>
                    </div>

                    @php($latestPosts = \App\Support\SiteData::recentPosts())
                    @if ($latestPosts->isNotEmpty())
                        <h2 class="posts-entry-title mb-4">Latest posts</h2>
                        <div class="post-entry-sidebar">
                            <ul class="list-unstyled">
                                @foreach ($latestPosts as $latest)
                                    <li class="mb-3">
                                        <a href="{{ $latest->url }}" class="d-flex align-items-center">
                                            <img src="{{ $latest->imageUrl('square') }}" alt="" class="me-4 rounded" width="80" height="80" loading="lazy">
                                            <div class="text">
                                                <h4>{{ $latest->title }}</h4>
                                                <div class="post-meta"><span>{{ $latest->published_at?->format('F j, Y') }}</span></div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mt-4"><a href="{{ route('home') }}" class="btn btn-primary">Back to the home page</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
