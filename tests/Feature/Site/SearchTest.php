<?php

use App\Models\Post;

test('search finds a published post by its title', function () {
    $post = Post::factory()->published()->create(['title' => 'Zebracorn Search Title']);

    $this->get(route('blog.search', ['q' => 'Zebracorn']))
        ->assertOk()
        ->assertSee($post->title);
});

test('search finds a published post by its excerpt', function () {
    $post = Post::factory()->published()->create([
        'title' => 'An Unrelated Title',
        'excerpt' => 'This excerpt mentions quokkafish once.',
    ]);

    $this->get(route('blog.search', ['q' => 'quokkafish']))
        ->assertOk()
        ->assertSee($post->title);
});

test('search never finds drafts', function () {
    $draft = Post::factory()->unpublished()->create(['title' => 'Zebracorn Secret Draft']);

    $this->get(route('blog.search', ['q' => 'Zebracorn']))
        ->assertOk()
        ->assertDontSee($draft->title);
});
