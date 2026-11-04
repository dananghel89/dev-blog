@extends('layouts.app')

@section('title', "Tag: #{$tag->name} - Full Stack Development Blog")
@section('meta_description', "Posts tagged with #{$tag->name}")
@section('meta_keywords', $tag->name)

@section('content')
    @include('partials.page-hero', [
        'title' => '#'.$tag->name,
        'subtitle' => 'Posts tagged with #'.$tag->name,
    ])

    @include('blog.partials.post-list', [
        'posts' => $posts,
        'emptyMessage' => 'No posts with this tag yet.',
    ])
@endsection
