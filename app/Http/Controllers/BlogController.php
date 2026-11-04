<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

/*
 * The post lists (index, category, tag) and the search results are Livewire components
 * (resources/views/livewire/blog/*). These actions only resolve the page and its meta data.
 */
class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function show(Post $post)
    {
        // Same rule as every list: published AND the publish date has passed (scheduled posts stay hidden).
        abort_unless(Post::published()->whereKey($post->getKey())->exists(), 404);

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with('category')
            ->latest('published_at')
            ->take(4)
            ->get();

        $post->load(['category', 'tags']);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function byCategory(Category $category)
    {
        return view('blog.category', compact('category'));
    }

    public function byTag(Tag $tag)
    {
        return view('blog.tag', compact('tag'));
    }

    public function search(Request $request)
    {
        // Only for the page title; the blog.search component reads ?q= itself.
        $query = trim((string) $request->query('q', ''));

        return view('blog.search', compact('query'));
    }
}
