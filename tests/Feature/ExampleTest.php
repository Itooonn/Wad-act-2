<?php

test('the application redirects guests to login from the home page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
