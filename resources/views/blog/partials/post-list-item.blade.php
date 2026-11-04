{{-- Blogy "blog-entry-search-item": square image on the left, text on the right. Param: $post --}}
<div class="blog-entry d-flex blog-entry-search-item">
    <a href="{{ $post->url }}" class="img-link me-4">
        <img src="{{ $post->imageUrl('square') }}" alt="{{ $post->title }}" class="img-fluid img-cover img-cover-square" loading="lazy">
    </a>
    <div>
        <span class="date">
            {{ $post->published_at?->format('M. jS, Y') }}
            @if ($post->category)
                &bullet; <a href="{{ $post->category->url }}">{{ $post->category->name }}</a>
            @endif
            &bullet; {{ $post->reading_time }}
        </span>
        <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
        <p>{{ Str::limit(strip_tags($post->excerpt), 160) }}</p>
        <p><a href="{{ $post->url }}" class="btn btn-sm btn-outline-primary">Read More</a></p>
    </div>
</div>
