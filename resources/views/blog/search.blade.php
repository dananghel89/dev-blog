@extends('layouts.app')

@section('title', 'Search Results: '.$query.' - Full Stack Development Blog')
@section('meta_description', 'Search results for "'.$query.'"')

@push('styles')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    @include('partials.page-hero', ['title' => 'Search'])

    <div class="section search-result-wrap">
        <div class="container">
            <div class="row posts-entry">
                <div class="col-lg-8">
                    <livewire:blog.search />
                </div>

                @include('blog.partials.sidebar')
            </div>
        </div>
    </div>
@endsection
