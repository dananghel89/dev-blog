<?php

use App\Models\Post;

test('the blog index lists published posts', function () {
    $post = Post::factory()->published()->create(['title' => 'Zebracorn Listed Post']);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertSee($post->title);
});

test('the blog index never lists drafts', function () {
    Post::factory()->published()->create();
    $draft = Post::factory()->unpublished()->create(['title' => 'Zebracorn Draft Post']);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertDontSee($draft->title);
});

test('the blog index is paginated by 10, newest first', function () {
    // 12 posts, one minute apart; "Paged Post 01" is the oldest.
    $posts = collect(range(1, 12))->map(fn (int $i) => Post::factory()->published()->create([
        'title' => sprintf('Paged Post %02d', $i),
        'created_at' => now()->subMinutes(100 - $i),
        'published_at' => now()->subMinutes(100 - $i),
    ]));

    $oldest = $posts->first();
    $newest = $posts->last();

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertSee($newest->title)
        ->assertDontSee($oldest->title);

    $this->get(route('blog.index', ['page' => 2]))
        ->assertOk()
        ->assertSee($oldest->title);
});
