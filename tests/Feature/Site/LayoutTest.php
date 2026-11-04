<?php

use App\Models\Category;
use App\Models\Post;

test('the layout loads assets through Vite, not from CDNs', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->not->toContain('cdn.jsdelivr.net')
        ->not->toContain('fonts.googleapis.com')
        ->not->toContain('code.jquery.com')
        ->not->toContain('placehold.co')
        ->not->toContain('css/styles.css')
        ->not->toContain('js/script.js');
});

test('the layout links the site favicons, and the files exist', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    foreach (['favicon.ico', 'favicon.png', 'apple-touch-icon.png'] as $file) {
        expect($html)->toContain(asset($file))
            ->and(public_path($file))->toBeFile();
    }

    expect($html)->not->toContain('favicon.svg');
});

test('public pages use the Dev Blog name', function (string $route) {
    config(['app.name' => 'Dev Blog']);

    $html = $this->get(route($route))->assertOk()->getContent();

    expect($html)->toContain('Dev Blog');
})->with(['home', 'blog.index', 'about', 'contact']);

test('the header lists categories that have published posts', function () {
    $withPosts = Category::factory()->create(['name' => 'Zebracorn Category']);
    Post::factory()->published()->create(['category_id' => $withPosts->id]);

    $onlyDrafts = Category::factory()->create(['name' => 'Quokkafish Category']);
    Post::factory()->unpublished()->create(['category_id' => $onlyDrafts->id]);

    $html = $this->get(route('home'))->assertOk()->getContent();
    $header = Str::before(Str::after($html, '<nav class="site-nav">'), '</nav>');

    expect($header)
        ->toContain('Zebracorn Category')
        ->toContain($withPosts->url)
        ->not->toContain('Quokkafish Category');
});

test('the footer lists the latest published posts', function () {
    $post = Post::factory()->published()->create(['title' => 'Zebracorn Footer Post']);
    $draft = Post::factory()->unpublished()->create(['title' => 'Quokkafish Footer Draft']);

    $footer = Str::after($this->get(route('about'))->assertOk()->getContent(), '<footer class="site-footer">');

    expect($footer)
        ->toContain($post->title)
        ->not->toContain($draft->title);
});

test('posts without an image use the local placeholder', function () {
    $post = Post::factory()->published()->create(['featured_image' => null]);

    expect($post->imageUrl('square'))->toEndWith('/images/placeholders/post-square.svg')
        ->and($post->imageUrl('unknown'))->toEndWith('/images/placeholders/post-horizontal.svg')
        ->and($post->hasImage())->toBeFalse();
});
