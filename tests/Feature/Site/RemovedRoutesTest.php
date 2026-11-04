<?php

use App\Models\User;

/*
 * Routes that must stay gone (review plan R01, R02, R11). Login and registration come back with
 * Filament (finish plan); privacy, terms, sitemap and the newsletter are rebuilt there too.
 */

test('there is no public registration', function () {
    $this->post('/register', [
        'name' => 'Intruder',
        'email' => 'intruder@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    expect(User::count())->toBe(0);
});

test('starter-kit auth and settings pages are gone', function (string $url) {
    $this->get($url)->assertNotFound();
})->with(['/login', '/register', '/forgot-password', '/two-factor-challenge', '/dashboard', '/settings/profile']);

test('privacy, terms and sitemap return 404 instead of a server error', function (string $url) {
    $this->get($url)->assertNotFound();
})->with(['/privacy', '/terms', '/sitemap.xml']);

test('the footer has no link to the missing privacy page', function () {
    $footer = Str::after($this->get(route('home'))->getContent(), '<footer class="site-footer">');

    expect($footer)->not->toContain('/privacy');
});

test('the fake newsletter routes are gone', function () {
    $this->post('/newsletter/subscribe', ['email' => 'reader@example.com'])->assertNotFound();
    $this->get('/newsletter/confirm/anything')->assertNotFound();
    $this->post('/newsletter/unsubscribe')->assertNotFound();
});

test('the home page has no newsletter form', function () {
    $this->get(route('home'))->assertOk()->assertDontSee('newsletter', false);
});
