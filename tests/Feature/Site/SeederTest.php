<?php

use App\Models\Category;
use App\Models\Post;
use Database\Factories\CategoryFactory;

/*
 * Review plan R04: the demo data must be clean, so the home page and the category pages make sense.
 */

test('seeding creates exactly the 8 categories with unique names and clean slugs', function () {
    $this->seed();

    $categories = Category::orderBy('name')->get();

    expect($categories)->toHaveCount(count(CategoryFactory::COLORS))
        ->and($categories->pluck('name')->sort()->values()->all())->toBe(collect(array_keys(CategoryFactory::COLORS))->sort()->values()->all())
        ->and($categories->pluck('slug')->all())->each->toMatch('/^[a-z-]+$/');
});

test('every seeded post belongs to one of the seeded categories and has a unique title', function () {
    $this->seed();

    $categoryIds = Category::pluck('id');

    expect(Post::count())->toBeGreaterThan(20)
        ->and(Post::whereNotIn('category_id', $categoryIds)->count())->toBe(0)
        ->and(Post::distinct()->count('title'))->toBe(Post::count())
        ->and(Post::where('featured_image', 'like', '%placehold.co%')->count())->toBe(0);
});

test('every seeded category has several posts', function () {
    $this->seed();

    Category::withCount('posts')->get()->each(
        fn (Category $category) => expect($category->posts_count)->toBeGreaterThanOrEqual(2)
    );
});
