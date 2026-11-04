@extends('layouts.app')

@section('title', 'About Me - Full Stack Developer')
@section('meta_description', 'Learn about the full-stack developer behind this blog, with experience in PHP, MySQL, HTML, CSS, and JavaScript.')
@section('meta_keywords', 'about, full stack developer, PHP developer, MySQL expert, web developer bio')

@section('content')
    <div class="site-cover site-cover-sm same-height overlay single-page bg-primary">
        <div class="container">
            <div class="row same-height justify-content-center">
                <div class="col-md-8">
                    <div class="post-entry text-center">
                        <h1 class="mb-4">About</h1>
                        <p class="text-white-50 mb-0">{{ config('project.tagline') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section sec-halfs py-0">
        <div class="container">
            <div class="half-content d-lg-flex align-items-stretch">
                <div class="img" style="background-image: url('{{ $avatar }}')" data-aos="fade-in" data-aos-delay="100" role="img" aria-label="Profile picture"></div>
                <div class="text">
                    <h2 class="heading text-primary mb-3">Hi, I'm {{ config('project.author') }}</h2>
                    <p class="mb-4">
                        I'm a full-stack developer with {{ $stats['years_experience'] }}+ years of experience building web
                        applications. I specialize in PHP, MySQL, and modern JavaScript, and I love sharing the challenges
                        I encounter in my daily work.
                    </p>
                    <p><a href="{{ route('blog.index') }}" class="btn btn-outline-primary py-2">Read the blog</a></p>
                    @include('partials.social-links', ['wrapper' => 'mt-4', 'class' => 'list-unstyled social social-inline'])
                </div>
            </div>
            <div class="half-content d-lg-flex align-items-stretch">
                <div class="img order-md-2" style="background-image: url('{{ asset('images/placeholders/post-horizontal.svg') }}')" data-aos="fade-in" role="img" aria-label="Code illustration"></div>
                <div class="text">
                    <h2 class="heading text-primary mb-3">Real problems, real solutions</h2>
                    <p class="mb-4">
                        This blog started as my personal development diary and grew into a resource for developers facing
                        similar challenges. Every post is born from a real problem I ran into at work: no theory without practice.
                    </p>
                    <p><a href="{{ route('contact') }}" class="btn btn-outline-primary py-2">Get in touch</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tech stack --}}
    @php
        $stackIcons = ['PHP' => 'bi-filetype-php', 'MySQL' => 'bi-database', 'Frontend' => 'bi-code-slash', 'Tools' => 'bi-tools'];
    @endphp
    <div class="section sec-features">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="posts-entry-title text-white">My Tech Stack</h2>
                </div>
            </div>
            <div class="row g-5">
                @foreach ($techStack as $group => $items)
                    <div class="col-12 col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="feature d-flex">
                            <span class="{{ $stackIcons[$group] ?? 'bi-check2-circle' }}" aria-hidden="true"></span>
                            <div>
                                <h3>{{ $group }}</h3>
                                <p>{{ implode(', ', $items) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $statLabels = [
            'years_experience' => 'Years of experience',
            'posts_written' => 'Posts written',
            'technologies' => 'Technologies',
            'countries_reached' => 'Countries reached',
        ];
    @endphp
    <div class="section bg-light">
        <div class="container">
            <div class="row text-center">
                @foreach ($stats as $key => $value)
                    <div class="col-6 col-lg-3 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="heading text-primary display-6 fw-bold">{{ $value }}</div>
                        <p class="mb-0">{{ $statLabels[$key] ?? Str::headline($key) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container text-center">
            <h2 class="heading text-primary mb-3">Let's connect</h2>
            <p class="mb-4">I'm always happy to discuss code, debug together, or just chat about development.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary">Send me a message</a>
        </div>
    </div>
@endsection
