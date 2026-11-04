<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Data shared by the layout partials (header, footer, sidebar).
 * Each query runs at most once per request (once()), however many partials use it.
 */
class SiteData
{
    /** Categories for the header dropdown: the ones with the most published posts. */
    public static function navCategories(int $limit = 6): Collection
    {
        return once(fn () => static::categoriesWithPublishedCount()->take($limit)->get());
    }

    /** All categories that have published posts, for the sidebar. */
    public static function sidebarCategories(): Collection
    {
        return once(fn () => static::categoriesWithPublishedCount()->get());
    }

    /** Tags with published posts, most used first, for the sidebar. */
    public static function sidebarTags(int $limit = 15): Collection
    {
        return once(fn () => Tag::query()
            ->whereHas('posts', fn (Builder $query) => $query->published())
            ->withCount(['posts' => fn (Builder $query) => $query->published()])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->take($limit)
            ->get());
    }

    /** The latest published posts, for the footer and the sidebar. */
    public static function recentPosts(int $limit = 3): Collection
    {
        return once(fn () => Post::published()
            ->with('category')
            ->latest('published_at')
            ->take($limit)
            ->get());
    }

    private static function categoriesWithPublishedCount(): Builder
    {
        return Category::query()
            ->whereHas('posts', fn (Builder $query) => $query->published())
            ->withCount(['posts' => fn (Builder $query) => $query->published()])
            ->orderByDesc('posts_count')
            ->orderBy('name');
    }
}
