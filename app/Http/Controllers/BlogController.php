<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with(['category', 'tags'])
            ->latest()
            ->paginate(10);
            
        // Sidebar data (categories, tags, recent posts) comes from the view composer.
        return view('blog.index', compact('posts'));
    }
    
    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }
        
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        $post->load(['category', 'tags']);
            
        return view('blog.show', compact('post', 'relatedPosts'));
    }
    
    public function byCategory(Category $category)
    {
        $posts = $category->posts()
            ->published()
            ->with('category')
            ->latest()
            ->paginate(10);
            
        return view('blog.category', compact('category', 'posts'));
    }
    
    public function byTag(Tag $tag)
    {
        $posts = $tag->posts()
            ->published()
            ->with('category')
            ->latest()
            ->paginate(10);
            
        return view('blog.tag', compact('tag', 'posts'));
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $posts = Post::published()
            ->with('category')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('blog.search', compact('posts', 'query'));
    }
}
