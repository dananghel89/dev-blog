<?php

/*
|--------------------------------------------------------------------------
| Site identity
|--------------------------------------------------------------------------
|
| Every value comes from .env and has a neutral default, so this public
| repository holds no personal values: put your real name, address and
| profile URLs only in your local .env (never committed).
|
*/

return [

    // Shown in the logo, the <title>, the footer and the emails.
    'name' => env('APP_NAME', 'Dev Blog'),

    // One-line description: default meta description, About cover, OG image.
    'tagline' => env('BLOG_TAGLINE', 'Real problems and solutions from full stack development'),

    // The person writing the blog: meta author and the About page.
    'author' => env('BLOG_AUTHOR', 'Blog Author'),

    // Recipient of the public contact form.
    'contact_email' => env('CONTACT_EMAIL', 'hello@example.com'),

    /*
    | Profile URLs. Only networks with a URL are rendered (footer, About,
    | Contact); leave a key empty to hide that network.
    */
    'social' => [
        'github' => env('SOCIAL_GITHUB'),
        'linkedin' => env('SOCIAL_LINKEDIN'),
        'x' => env('SOCIAL_X'),
        'stackoverflow' => env('SOCIAL_STACKOVERFLOW'),
    ],

];
