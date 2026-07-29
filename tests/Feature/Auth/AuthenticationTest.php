<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    config()->set('services.recaptcha.secret_key', 'recaptcha-secret');
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'g-recaptcha-response' => 'recaptcha-token',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    config()->set('services.recaptcha.secret_key', 'recaptcha-secret');
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        'g-recaptcha-response' => 'recaptcha-token',
    ]);

    $this->assertGuest();
});

test('users can not authenticate without completing the captcha', function () {
    $user = User::factory()->create();

    config()->set('services.recaptcha.secret_key', 'recaptcha-secret');

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('g-recaptcha-response');
    $this->assertGuest();
});

test('users can not authenticate with an invalid captcha response', function () {
    $user = User::factory()->create();

    config()->set('services.recaptcha.secret_key', 'recaptcha-secret');
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'g-recaptcha-response' => 'invalid-token',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('g-recaptcha-response');
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
