<?php

use App\Mail\ContactFormMail;

/*
 * Site identity comes from config('project.*') (backed by .env), never from hard-coded markup.
 */

beforeEach(function () {
    config([
        'project.name' => 'Zebracorn Blog',
        'project.tagline' => 'Quokkafish tagline',
        'project.author' => 'Ada Example',
        'project.social' => ['github' => null, 'linkedin' => null, 'x' => null, 'stackoverflow' => null],
    ]);
});

test('the configured name, tagline and author appear on the home page', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('<title>Full Stack Development Blog - Zebracorn Blog</title>')
        ->toContain('<meta name="author" content="Ada Example">')
        ->toContain('<meta property="og:site_name" content="Zebracorn Blog">')
        ->toContain('Zebracorn Blog<span class="text-primary">.</span>')
        ->toContain('Quokkafish tagline')
        ->not->toContain('Your Name');
});

test('the About page greets with the configured author', function () {
    $this->get(route('about'))->assertOk()
        ->assertSee("Hi, I'm Ada Example", false)
        ->assertDontSee('Your Name');
});

test('pages without their own description use the tagline', function () {
    $html = $this->get('/no-such-page')->assertNotFound()->getContent(); // the 404 page sets no description

    expect($html)->toContain('<meta name="description" content="Quokkafish tagline">');
});

test('only configured social networks are linked', function () {
    config(['project.social.github' => 'https://github.com/ada-example']);

    foreach (['home', 'about', 'contact'] as $route) {
        $html = $this->get(route($route))->assertOk()->getContent();

        expect($html)
            ->toContain('href="https://github.com/ada-example"')
            ->toContain('aria-label="GitHub"')
            ->not->toContain('aria-label="LinkedIn"')
            ->not->toContain('aria-label="X (Twitter)"')
            ->not->toContain('aria-label="Stack Overflow"');
    }
});

test('with no social networks set, the footer has no Social block', function () {
    $footer = Str::after($this->get(route('home'))->assertOk()->getContent(), '<footer class="site-footer">');

    expect($footer)
        ->not->toContain('<h3>Social</h3>')
        ->not->toContain('bi-github');
});

test('the header and footer have no dead href="#" links', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    $header = Str::before(Str::after($html, '<nav class="site-nav">'), '</nav>');
    $footer = Str::after($html, '<footer class="site-footer">');

    expect($header)->not->toContain('href="#"')
        ->and($footer)->not->toContain('href="#"')
        ->and($footer)->toContain('<a href="https://untree.co">Untree.co</a>');
});

test('the contact email uses the configured name', function () {
    $html = (new ContactFormMail([
        'name' => 'Sender',
        'email' => 'sender@example.com',
        'subject' => 'Hello',
        'message' => 'Hi there',
    ]))->render();

    expect($html)->toContain('Zebracorn Blog')->not->toContain('#6366f1');
});
