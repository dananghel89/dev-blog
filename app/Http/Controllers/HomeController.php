<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function index()
    {
        // Newest published posts: 5 for the featured grid, 4 for the small grid, 6 for "More posts".
        $latest = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(15)
            ->get();

        $featuredPosts = $latest->take(5)->values();
        $gridPosts = $latest->slice(5, 4)->values();
        $morePosts = $latest->slice(9, 6)->values();

        // The two categories with the most published posts, each with its 5 newest posts.
        $categoryBlocks = Category::query()
            ->whereHas('posts', fn (Builder $query) => $query->published())
            ->withCount(['posts' => fn (Builder $query) => $query->published()])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->take(2)
            ->get()
            ->each(fn (Category $category) => $category->setRelation(
                'posts',
                $category->posts()->published()->latest('published_at')->take(5)->get()
            ));

        return view('pages.home', compact('featuredPosts', 'gridPosts', 'morePosts', 'categoryBlocks'));
    }
}
