<?php

use App\Models\Post;

/*
 * Every page shares an absolute image URL: the post's own image, or public/images/default-og.png.
 */

function ogImages(string $html): array
{
    preg_match('/<meta property="og:image" content="([^"]*)">/', $html, $og);
    preg_match('/<meta name="twitter:image" content="([^"]*)">/', $html, $twitter);

    return [$og[1] ?? null, $twitter[1] ?? null];
}

test('the default OG image exists at 1200x630', function () {
    $path = public_path('images/default-og.png');

    expect($path)->toBeFile()
        ->and(array_slice(getimagesize($path), 0, 2))->toBe([1200, 630]);
});

test('pages fall back to the default OG image', function (string $route) {
    [$og, $twitter] = ogImages($this->get(route($route))->assertOk()->getContent());

    expect($og)->toBe(asset('images/default-og.png'))
        ->and($twitter)->toBe($og);
})->with(['home', 'blog.index', 'about', 'contact']);

test('a post without an image uses the default OG image', function () {
    $post = Post::factory()->published()->create(['featured_image' => null]);

    [$og, $twitter] = ogImages($this->get($post->url)->assertOk()->getContent());

    expect($og)->toBe(asset('images/default-og.png'))
        ->and($twitter)->toBe($og);
});

test('a post image is shared as an absolute URL', function (string $stored, string $expected) {
    $post = Post::factory()->published()->create(['featured_image' => $stored]);

    [$og, $twitter] = ogImages($this->get($post->url)->assertOk()->getContent());

    expect($og)->toBe($expected === 'url' ? url($stored) : $expected)
        ->and($og)->toStartWith('http')
        ->and($twitter)->toBe($og);
})->with([
    'full URL' => ['https://images.example.com/cover.jpg', 'https://images.example.com/cover.jpg'],
    'root-relative path' => ['/images/cover.jpg', 'url'],
]);
