<?php

use App\Models\Category;
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

test('the home page shows category blocks that link to working category pages', function () {
    $category = Category::factory()->create(['name' => 'Zebracorn Block']);
    Post::factory()->published()->count(2)->create(['category_id' => $category->id]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Zebracorn Block')
        ->assertSee($category->url, false);

    $this->get($category->url)->assertOk();
});

test('the home page has an empty state when there are no posts', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('No posts yet');
});
