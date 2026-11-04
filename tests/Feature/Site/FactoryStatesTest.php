<?php

use App\Models\Category;
use App\Models\Post;

test('the named category state gives a clean slug and the category colour', function () {
    $category = Category::factory()->named('Vue.js')->create();

    expect($category->name)->toBe('Vue.js')
        ->and($category->slug)->toBe('vuejs')
        ->and($category->color)->toBe('#4fc08d');
});

test('a scheduled post is published in the future and not yet visible', function () {
    $post = Post::factory()->scheduled()->create();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at->isFuture())->toBeTrue()
        ->and(Post::published()->whereKey($post->id)->exists())->toBeFalse();
});
