<?php

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/*
 * Live search over published posts. The query is kept in the URL (?q=...), so the first render
 * is server-side and results pages can be shared. Queries shorter than MIN_LENGTH aren't run.
 */
new class extends Component
{
    use WithPagination;

    public const MIN_LENGTH = 2;

    public const PER_PAGE = 10;

    #[Url(as: 'q', except: '')]
    public string $q = '';

    public function updatedQ(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function term(): string
    {
        return trim($this->q);
    }

    #[Computed]
    public function results(): ?LengthAwarePaginator
    {
        if (mb_strlen($this->term) < self::MIN_LENGTH) {
            return null;
        }

        // Escape LIKE wildcards so "%" or "_" match literally instead of everything. An explicit
        // ESCAPE character is needed: MySQL defaults to "\", SQLite (used by the tests) has no default.
        $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $this->term).'%';

        return Post::published()
            ->where(fn ($query) => $query
                ->whereRaw("title LIKE ? ESCAPE '!'", [$like])
                ->orWhereRaw("excerpt LIKE ? ESCAPE '!'", [$like])
                ->orWhereRaw("content LIKE ? ESCAPE '!'", [$like]))
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

<div>
    <div class="search-form-wrap mb-5">
        <form class="sidebar-search-form" role="search" wire:submit.prevent>
            <label for="search-q" class="visually-hidden">Search posts</label>
            <span class="bi-search" aria-hidden="true"></span>
            <input type="search" id="search-q" class="form-control" placeholder="Type a keyword..."
                   wire:model.live.debounce.300ms="q" autocomplete="off" autofocus>
        </form>
    </div>

    <div aria-live="polite" wire:loading.class="opacity-50">
        @if ($this->results === null)
            <p>Type at least {{ $this::MIN_LENGTH }} characters to search the blog.</p>
        @else
            <div class="heading">
                {{ $this->results->total() }} {{ Str::plural('result', $this->results->total()) }} for "{{ $this->term }}"
            </div>

            @forelse ($this->results as $post)
                @include('blog.partials.post-list-item', ['post' => $post])
            @empty
                <p>Nothing matched your search. Try another keyword.</p>
            @endforelse

            @if ($this->results->hasPages())
                <div class="row text-start pt-5 border-top">
                    <div class="col-md-12">
                        {{ $this->results->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
