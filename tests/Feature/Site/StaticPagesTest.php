<?php

test('static pages render', function (string $route) {
    $this->get(route($route))->assertOk();
})->with(['about', 'contact']);

test('About shows the placeholder avatar until public/images/avatar.jpg exists', function () {
    $photo = public_path('images/avatar.jpg');
    $created = false;

    if (! is_file($photo)) {
        $this->get(route('about'))->assertOk()->assertSee(asset('images/placeholders/avatar.svg'));

        file_put_contents($photo, 'test');
        $created = true;
    }

    try {
        $this->get(route('about'))->assertOk()
            ->assertSee(asset('images/avatar.jpg'))
            ->assertDontSee('placeholders/avatar.svg');
    } finally {
        if ($created) {
            unlink($photo);
        }
    }
});
