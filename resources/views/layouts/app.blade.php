{{--
    Layout based on the Blogy template by Untree.co (https://untree.co/),
    licensed under CC BY 3.0 (https://creativecommons.org/licenses/by/3.0/). Modified for this application.
    The Untree.co credit in partials/footer.blade.php must stay (https://untree.co/license/).
--}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') - {{ config('project.name') }}</title>

    {{-- SEO --}}
    <meta name="description" content="@yield('meta_description', config('project.tagline'))">
    <meta name="keywords" content="@yield('meta_keywords', 'PHP, MySQL, HTML, CSS, JavaScript, Laravel, web development, coding challenges')">
    <meta name="author" content="{{ config('project.author') }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:title" content="@yield('title', config('project.name'))">
    <meta property="og:description" content="@yield('meta_description', config('project.tagline'))">
    <meta property="og:site_name" content="{{ config('project.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og.png'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('project.name'))">
    <meta name="twitter:description" content="@yield('meta_description', config('project.tagline'))">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    {{-- Hide the preloader when JavaScript is off, so the page is never stuck behind it. --}}
    <noscript><style>#overlayer, .loader { display: none !important; }</style></noscript>

    @stack('styles')
</head>
<body>

    {{-- Off-canvas mobile menu: filled by resources/js/theme/navbar.js from the desktop menu. --}}
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="bi bi-x-lg js-menu-toggle" role="button" aria-label="Close menu"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    @include('partials.header')

    <main>
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="container mt-4">
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        @endforeach

        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Preloader (theme) --}}
    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
