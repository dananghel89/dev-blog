<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $featuredPosts = Post::published()
            ->latest()
            ->take(3)
            ->get();
            
        $categories = [
            'PHP' => Post::whereHas('category', fn($q) => $q->where('name', 'PHP'))->count(),
            'MySQL' => Post::whereHas('category', fn($q) => $q->where('name', 'MySQL'))->count(),
            'HTML/CSS' => Post::whereHas('category', fn($q) => $q->whereIn('name', ['HTML', 'CSS']))->count(),
            'JavaScript' => Post::whereHas('category', fn($q) => $q->where('name', 'JavaScript'))->count(),
        ];
        
        return view('pages.home', compact('featuredPosts', 'categories'));
    }
}
