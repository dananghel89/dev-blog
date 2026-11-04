<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

/*
 * The "other" post is the oldest one (created and published a year ago; the factory publishes
 * within the last 6 months), and there are 3 newer posts, so it can't
 * show up in a "recent posts" sidebar or footer block (the Blogy layout has both).
 * That keeps the assertDontSee checks about the listing only.
 */

test('a category page lists only its published posts', function () {
    $category = Category::factory()->create();

    $other = Post::factory()->published()->create([
        'title' => 'Zebracorn Other Category Post',
        'created_at' => now()->subYear(),
        'published_at' => now()->subYear(),
    ]);
    $draft = Post::factory()->unpublished()->create([
        'title' => 'Zebracorn Draft In Category',
        'category_id' => $category->id,
    ]);
    $posts = Post::factory()->published()->count(3)->sequence(
        ['title' => 'Zebracorn In Category One'],
        ['title' => 'Zebracorn In Category Two'],
        ['title' => 'Zebracorn In Category Three'],
    )->create(['category_id' => $category->id]);

    $response = $this->get(route('blog.category', $category->slug))->assertOk();

    foreach ($posts as $post) {
        $response->assertSee($post->title);
    }
    $response->assertDontSee($other->title)->assertDontSee($draft->title);
});

test('a tag page lists only its published posts', function () {
    $tag = Tag::factory()->create();

    $other = Post::factory()->published()->create([
        'title' => 'Zebracorn Untagged Post',
        'created_at' => now()->subYear(),
        'published_at' => now()->subYear(),
    ]);
    $draft = Post::factory()->unpublished()->create(['title' => 'Zebracorn Tagged Draft']);
    $draft->tags()->attach($tag);

    $posts = Post::factory()->published()->count(3)->sequence(
        ['title' => 'Zebracorn Tagged One'],
        ['title' => 'Zebracorn Tagged Two'],
        ['title' => 'Zebracorn Tagged Three'],
    )->create();
    $posts->each(fn (Post $post) => $post->tags()->attach($tag));

    $response = $this->get(route('blog.tag', $tag->slug))->assertOk();

    foreach ($posts as $post) {
        $response->assertSee($post->title);
    }
    $response->assertDontSee($other->title)->assertDontSee($draft->title);
});

test('an unknown category or tag returns 404', function () {
    $this->get(route('blog.category', 'no-such-category'))->assertNotFound();
    $this->get(route('blog.tag', 'no-such-tag'))->assertNotFound();
});
