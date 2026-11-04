<?php

use App\Mail\ContactFormMail;

/*
 * Review plan R08: the contact mail must show line breaks, encode the reply subject and have no dead links.
 */

function contactMail(array $overrides = []): string
{
    return (new ContactFormMail(array_merge([
        'name' => 'Jane Reader',
        'email' => 'jane@example.com',
        'subject' => 'Tom & Jerry',
        'message' => "line1\nline2 <b>not bold</b>",
    ], $overrides)))->render();
}

test('the message keeps its line breaks and is escaped exactly once', function () {
    $html = contactMail();

    expect($html)
        ->toContain('line1<br />')
        ->not->toContain('&lt;br')
        ->toContain('&lt;b&gt;not bold&lt;/b&gt;');
});

test('the reply link has a URL-encoded subject', function () {
    expect(contactMail())->toContain('mailto:jane@example.com?subject='.rawurlencode('Re: Tom & Jerry'));
});

test('the mail has no dead links', function () {
    expect(contactMail())->not->toContain('href="#"');
});
