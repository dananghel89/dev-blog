<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

/*
 * Review plan R15: generated slugs must be unique instead of failing on the unique index.
 */

test('posts with the same title get unique slugs', function () {
    $first = Post::factory()->published()->create(['title' => 'Same Title', 'slug' => null]);
    $second = Post::factory()->published()->create(['title' => 'Same Title', 'slug' => null]);
    $third = Post::factory()->published()->create(['title' => 'Same Title', 'slug' => null]);

    expect([$first->slug, $second->slug, $third->slug])->toBe(['same-title', 'same-title-2', 'same-title-3']);
});

test('categories and tags with the same name get unique slugs', function () {
    expect(Category::factory()->create(['name' => 'PHP', 'slug' => null])->slug)->toBe('php')
        ->and(Category::factory()->create(['name' => 'PHP', 'slug' => null])->slug)->toBe('php-2')
        ->and(Tag::factory()->create(['name' => 'tips', 'slug' => null])->slug)->toBe('tips')
        ->and(Tag::factory()->create(['name' => 'tips', 'slug' => null])->slug)->toBe('tips-2');
});

test('an explicit slug is kept', function () {
    expect(Post::factory()->create(['title' => 'Anything', 'slug' => 'custom-slug'])->slug)->toBe('custom-slug');
});
