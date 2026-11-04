<?php

use App\Models\Post;

test('the home page shows the latest published posts', function () {
    $post = Post::factory()->published()->create(['title' => 'Zebracorn Home Post']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($post->title);
});

test('the home page never shows drafts', function () {
    Post::factory()->published()->create();
    $draft = Post::factory()->unpublished()->create(['title' => 'Zebracorn Draft Post']);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee($draft->title);
});
