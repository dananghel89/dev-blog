<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

/*
 * Review plan R05, R06, R07, R14, N01: only published posts count, everywhere.
 */

test('a scheduled post is not readable at its URL yet', function () {
    $post = Post::factory()->scheduled()->create();

    $this->get(route('blog.show', $post->slug))->assertNotFound();
});

test('a category with only drafts gets no block on the home page', function () {
    $draftsOnly = Category::factory()->create(['name' => 'Quokkafish Drafts']);
    Post::factory()->unpublished()->count(3)->create(['category_id' => $draftsOnly->id]);
    Post::factory()->published()->create();

    $this->get(route('home'))->assertOk()->assertDontSee('Quokkafish Drafts');
});

test('sidebar category and tag counts ignore drafts and scheduled posts', function () {
    $category = Category::factory()->create(['name' => 'Zebracorn Counted']);
    $tag = Tag::factory()->create(['name' => 'zebracorn-tag-counted']);

    $published = Post::factory()->published()->create(['category_id' => $category->id]);
    $published->tags()->attach($tag);
    Post::factory()->unpublished()->create(['category_id' => $category->id])->tags()->attach($tag);
    Post::factory()->scheduled()->create(['category_id' => $category->id])->tags()->attach($tag);

    $html = $this->get(route('blog.index'))->assertOk()->getContent();

    expect($html)->toMatch('/Zebracorn Counted\s*<span>\(1\)<\/span>/');
});

test('with no posts, the home page has no link to a missing post', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('/blog/%23', false)
        ->assertDontSee('/blog/#', false);
});

test('related posts are the newest by publish date', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->published()->create(['category_id' => $category->id]);
    Post::factory()->published()->create([
        'title' => 'Zebracorn Old Related', 'category_id' => $category->id,
        'published_at' => now()->subMonths(2), 'created_at' => now(),
    ]);
    Post::factory()->published()->create([
        'title' => 'Zebracorn New Related', 'category_id' => $category->id,
        'published_at' => now()->subDay(), 'created_at' => now()->subYear(),
    ]);

    $html = $this->get(route('blog.show', $post->slug))->assertOk()->getContent();

    // Only the "More … Posts" section at the bottom, not the sidebar's recent posts.
    $related = Str::after($html, 'posts-entry-sm');

    expect(strpos($related, 'Zebracorn New Related'))->toBeLessThan(strpos($related, 'Zebracorn Old Related'));
});
