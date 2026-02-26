<header class="header bg-black border-bottom border-secondary sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <!-- Logo/Brand -->
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                Dev Blog
                <small class="d-none d-md-inline text-secondary fs-6 ms-2">// full-stack dev blog</small>
            </a>
            
            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active text-primary' : 'text-light' }}" 
                           href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active text-primary' : 'text-light' }}" 
                           href="{{ route('about') }}">
                            <i class="bi bi-person me-1"></i> About
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog.*') ? 'active text-primary' : 'text-light' }}" 
                           href="{{ route('blog.index') }}">
                            <i class="bi bi-journal-bookmark-fill me-1"></i> Blog
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active text-primary' : 'text-light' }}" 
                           href="{{ route('contact') }}">
                            <i class="bi bi-envelope me-1"></i> Contact
                        </a>
                    </li>
                    
                    <!-- Search Toggle Button -->
                    <li class="nav-item ms-lg-3">
                        <button class="btn btn-outline-secondary btn-sm" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#searchBar" 
                                aria-expanded="false" aria-controls="searchBar">
                            <i class="bi bi-search"></i>
                        </button>
                    </li>
                    
                    <!-- Dark/Light Mode Toggle -->
                    <li class="nav-item">
                        <button class="btn btn-outline-secondary btn-sm ms-2" id="themeToggle" 
                                onclick="toggleTheme()">
                            <i class="bi bi-moon-fill" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Search Bar Collapse -->
    <div class="collapse bg-dark border-top border-secondary" id="searchBar">
        <div class="container py-3">
            <form action="{{ route('blog.search') }}" method="GET" class="d-flex">
                <input class="form-control form-control-lg bg-black text-light border-secondary" 
                       type="search" name="q" placeholder="Search blog posts..." 
                       aria-label="Search" required>
                <button class="btn btn-primary ms-2" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <div class="mt-2">
                <small class="text-secondary">Popular searches:</small>
                @php
                    $popularTags = \App\Models\Tag::withCount('posts')
                        ->orderBy('posts_count', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @foreach($popularTags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none ms-1">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>