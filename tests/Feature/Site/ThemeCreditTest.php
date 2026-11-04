<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

/*
 * The Blogy license (https://untree.co/license/) requires the Untree.co backlink in the footer of every page.
 */

test('every public page shows the Untree.co credit in the footer', function (string $route, array $parameters) {
    $category = Category::factory()->create(['slug' => 'credit-category']);
    $tag = Tag::factory()->create(['slug' => 'credit-tag']);
    $post = Post::factory()->published()->create(['slug' => 'credit-post', 'category_id' => $category->id]);
    $post->tags()->attach($tag);

    $html = $this->get(route($route, $parameters))->assertOk()->getContent();

    expect(Str::after($html, '<footer class="site-footer">'))
        ->toContain('<a href="https://untree.co">Untree.co</a>');
})->with([
    'home' => ['home', []],
    'blog' => ['blog.index', []],
    'post' => ['blog.show', ['credit-post']],
    'category' => ['blog.category', ['credit-category']],
    'tag' => ['blog.tag', ['credit-tag']],
    'search' => ['blog.search', ['q' => 'php']],
    'about' => ['about', []],
    'contact' => ['contact', []],
]);
