<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

test('a published post shows its title, category and tags', function () {
    $category = Category::factory()->create(['name' => 'Zebracorn Category']);
    $tag = Tag::factory()->create(['name' => 'zebracorn-tag']);
    $post = Post::factory()->published()->create([
        'title' => 'Zebracorn Post Title',
        'category_id' => $category->id,
    ]);
    $post->tags()->attach($tag);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee($category->name)
        ->assertSee($tag->name);
});

test('a draft post returns 404', function () {
    $draft = Post::factory()->unpublished()->create();

    $this->get(route('blog.show', $draft->slug))->assertNotFound();
});

test('an unknown post slug returns 404', function () {
    $this->get(route('blog.show', 'no-such-post'))->assertNotFound();
});
