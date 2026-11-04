@extends('layouts.app')

@section('title', "Category: {$category->name} - Full Stack Development Blog")
@section('meta_description', "Posts about {$category->name} development - real challenges and solutions")
@section('meta_keywords', $category->name)

@section('content')
    @include('partials.page-hero', [
        'title' => $category->name,
        'subtitle' => $category->description ?: 'Posts in this category',
    ])

    @include('blog.partials.post-list', [
        'category' => $category,
        'emptyMessage' => 'No posts in this category yet.',
    ])
@endsection
