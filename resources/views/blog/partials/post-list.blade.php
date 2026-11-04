{{--
    Post list + sidebar (Blogy "search-result-wrap"), shared by the blog index, category and tag pages.
    The list itself is the blog.post-list Livewire component. Params: optional $category, $tag, $emptyMessage.
--}}
<div class="section search-result-wrap">
    <div class="container">
        <div class="row posts-entry">
            <div class="col-lg-8">
                <livewire:blog.post-list
                    :category="$category ?? null"
                    :tag="$tag ?? null"
                    :empty-message="$emptyMessage ?? 'No posts yet.'" />
            </div>

            @include('blog.partials.sidebar')
        </div>
    </div>
</div>
