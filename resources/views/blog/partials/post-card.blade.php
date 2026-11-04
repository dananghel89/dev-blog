{{--
    Blogy "blog-entry" card. Params: $post, optional $ratio (horizontal|square), $excerptLength,
    $button (true: "Read More" button, false: "Continue Reading" link).
--}}
@php($ratio ??= 'horizontal')
<div class="blog-entry">
    <a href="{{ $post->url }}" class="img-link">
        <img src="{{ $post->imageUrl($ratio) }}" alt="{{ $post->title }}" class="img-fluid img-cover img-cover-{{ $ratio }}" loading="lazy">
    </a>
    <span class="date">
        {{ $post->published_at?->format('M. jS, Y') }}
        @if ($post->category)
            &bullet; <a href="{{ $post->category->url }}">{{ $post->category->name }}</a>
        @endif
    </span>
    <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
    <p>{{ Str::limit(strip_tags($post->excerpt), $excerptLength ?? 110) }}</p>
    @if ($button ?? true)
        <p><a href="{{ $post->url }}" class="btn btn-sm btn-outline-primary">Read More</a></p>
    @else
        <p><a href="{{ $post->url }}" class="read-more">Continue Reading</a></p>
    @endif
</div>
