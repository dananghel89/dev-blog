{{-- Blog sidebar (Blogy). Data from the view composer in AppServiceProvider. --}}
<div class="col-lg-4 sidebar">

    <div class="sidebar-box search-form-wrap mb-4">
        <form action="{{ route('blog.search') }}" method="GET" class="sidebar-search-form" role="search">
            <span class="bi-search" aria-hidden="true"></span>
            <input type="search" name="q" class="form-control" value="{{ request('q') }}"
                   placeholder="Type a keyword and hit enter" aria-label="Search posts" minlength="2" required>
        </form>
    </div>

    @if ($sidebarPosts->isNotEmpty())
        <div class="sidebar-box">
            <h3 class="heading">Recent Posts</h3>
            <div class="post-entry-sidebar">
                <ul>
                    @foreach ($sidebarPosts as $sidebarPost)
                        <li>
                            <a href="{{ $sidebarPost->url }}">
                                <img src="{{ $sidebarPost->imageUrl('square') }}" alt="" class="me-4 rounded" loading="lazy">
                                <div class="text">
                                    <h4>{{ $sidebarPost->title }}</h4>
                                    <div class="post-meta">
                                        <span class="mr-2">{{ $sidebarPost->published_at?->format('F j, Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($sidebarCategories->isNotEmpty())
        <div class="sidebar-box">
            <h3 class="heading">Categories</h3>
            <ul class="categories">
                @foreach ($sidebarCategories as $sidebarCategory)
                    <li><a href="{{ $sidebarCategory->url }}">{{ $sidebarCategory->name }} <span>({{ $sidebarCategory->posts_count }})</span></a></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($sidebarTags->isNotEmpty())
        <div class="sidebar-box">
            <h3 class="heading">Tags</h3>
            <ul class="tags">
                @foreach ($sidebarTags as $sidebarTag)
                    <li><a href="{{ $sidebarTag->url }}">{{ $sidebarTag->name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
