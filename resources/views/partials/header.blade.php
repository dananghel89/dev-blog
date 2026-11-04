{{-- Header based on the Blogy template by Untree.co (CC BY 3.0), modified. --}}
<nav class="site-nav">
    <div class="container">
        <div class="menu-bg-wrap">
            <div class="site-navigation">
                <div class="row g-0 align-items-center">
                    <div class="col-5 col-lg-2">
                        <a href="{{ route('home') }}" class="logo m-0 float-start text-nowrap">Dev Blog<span class="text-primary">.</span></a>
                    </div>

                    <div class="col-5 col-lg-8 text-center">
                        <form action="{{ route('blog.search') }}" method="GET" class="search-form d-inline-block d-lg-none" role="search">
                            <input type="search" name="q" class="form-control" placeholder="Search..." aria-label="Search posts" minlength="2" required>
                            <span class="bi-search" aria-hidden="true"></span>
                        </form>

                        <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu mx-auto">
                            <li @class(['active' => request()->routeIs('home')])>
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li @class(['active' => request()->routeIs('blog.index', 'blog.show', 'blog.search', 'blog.tag')])>
                                <a href="{{ route('blog.index') }}">Blog</a>
                            </li>
                            @if ($navCategories->isNotEmpty())
                                <li @class(['has-children', 'active' => request()->routeIs('blog.category')])>
                                    <a href="{{ route('blog.index') }}">Categories</a>
                                    <ul class="dropdown">
                                        @foreach ($navCategories as $navCategory)
                                            <li><a href="{{ $navCategory->url }}">{{ $navCategory->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            <li @class(['active' => request()->routeIs('about')])>
                                <a href="{{ route('about') }}">About</a>
                            </li>
                            <li @class(['active' => request()->routeIs('contact')])>
                                <a href="{{ route('contact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-2 text-end">
                        <a href="#" class="burger ms-auto float-end site-menu-toggle js-menu-toggle d-inline-block d-lg-none light" aria-label="Open menu">
                            <span></span>
                        </a>
                        <form action="{{ route('blog.search') }}" method="GET" class="search-form d-none d-lg-inline-block" role="search">
                            <input type="search" name="q" class="form-control" placeholder="Search..." aria-label="Search posts" minlength="2" required>
                            <span class="bi-search" aria-hidden="true"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
