@extends('layouts.app')

@section('title', 'Search Results: '.($query ?? '').' - Full Stack Development Blog')
@section('meta_description', 'Search results for "'.($query ?? '').'"')

@push('styles')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.page-hero', [
        'title' => 'Search',
        'subtitle' => $posts->total() > 0
            ? 'Found '.$posts->total().' '.Str::plural('post', $posts->total()).' matching "'.$query.'"'
            : 'No posts found for "'.$query.'"',
    ])

    @include('blog.partials.post-list', [
        'posts' => $posts,
        'emptyMessage' => 'Nothing matched your search. Try another keyword.',
    ])
@endsection
