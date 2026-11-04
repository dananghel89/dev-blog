<?php

test('static pages render', function (string $route) {
    $this->get(route($route))->assertOk();
})->with(['about', 'contact']);
