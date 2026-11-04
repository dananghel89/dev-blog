{{--
    Paginated post list + sidebar (Blogy "search-result-wrap"), shared by blog index, category, tag and search.
    Params: $posts (paginator), optional $emptyMessage.
    (Moves into the blog.post-list Livewire component in Phase 6 of the Blogy theme plan.)
--}}
<div class="section search-result-wrap">
    <div class="container">
        <div class="row posts-entry">
            <div class="col-lg-8">
                @forelse ($posts as $post)
                    @include('blog.partials.post-list-item', ['post' => $post])
                @empty
                    <p class="lead">{{ $emptyMessage ?? 'No posts yet.' }}</p>
                    <p><a href="{{ route('blog.index') }}" class="btn btn-outline-primary">Browse all posts</a></p>
                @endforelse

                @if ($posts->hasPages())
                    <div class="row text-start pt-5 border-top">
                        <div class="col-md-12">
                            {{ $posts->withQueryString()->links('vendor.pagination.blogy') }}
                        </div>
                    </div>
                @endif
            </div>

            @include('blog.partials.sidebar')
        </div>
    </div>
</div>
