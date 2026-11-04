@extends('layouts.app')

@section('title', 'Blog - Full Stack Development Challenges')
@section('meta_description', 'Explore real-world development challenges and solutions in PHP, MySQL, HTML, CSS, and JavaScript.')
@section('meta_keywords', 'blog, PHP blog, MySQL tips, web development, coding challenges')

@section('content')
    @include('partials.page-hero', ['title' => 'Blog'])

    @include('blog.partials.post-list')
@endsection
