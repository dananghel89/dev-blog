<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

/*
 * Paginated list of published posts for the blog index, a category or a tag (Blogy "blog-entry-search-item" markup).
 * The page number lives in the URL (?page=N), so pages stay shareable and crawlable.
 */
new class extends Component
{
    use WithPagination;

    public const PER_PAGE = 10;

    #[Locked]
    public ?Category $category = null;

    #[Locked]
    public ?Tag $tag = null;

    public string $emptyMessage = 'No posts yet.';

    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        return Post::published()
            ->when($this->category, fn ($query) => $query->whereBelongsTo($this->category))
            ->when($this->tag, fn ($query) => $query->whereHas('tags', fn ($tags) => $tags->whereKey($this->tag->getKey())))
            ->with('category')
            ->latest('published_at')
            ->paginate(self::PER_PAGE);
    }

    public function paginationView(): string
    {
        return 'vendor.pagination.blogy-livewire';
    }
};
?>

<div wire:loading.class="opacity-50" class="post-list">
    @forelse ($this->posts as $post)
        @include('blog.partials.post-list-item', ['post' => $post])
    @empty
        <p class="lead">{{ $emptyMessage }}</p>
        <p><a href="{{ route('blog.index') }}" class="btn btn-outline-primary">Browse all posts</a></p>
    @endforelse

    @if ($this->posts->hasPages())
        <div class="row text-start pt-5 border-top">
            <div class="col-md-12">
                {{ $this->posts->links() }}
            </div>
        </div>
    @endif
</div>
