<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function createUser(array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'username' => 'john_doe',
        'email' => 'john@example.com',
        'password_hash' => Hash::make('secret123'),
    ], $overrides));
}

function signInPayload(array $overrides = []): array
{
    return array_merge([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ], $overrides);
}

test('user can sign in', function () {
    createUser();

    $this->postJson('/api/sign-in', signInPayload())->assertOk()->assertJsonPath('username', 'john_doe')
        ->assertJsonPath('email', 'john@example.com')
        ->assertJsonMissingPath('password_hash');
});

test('user is authenticated after sign in', function () {
    createUser();

    $this->postJson('/api/sign-in', signInPayload());

    $this->assertAuthenticated('web');
});

test('email is case insensitive', function () {
    createUser();

    $this->postJson('/api/sign-in', signInPayload(['email' => 'John@Example.com']))->assertOk();

    $this->assertAuthenticated('web');
});

test('wrong password is rejected', function () {
    createUser();

    $this->postJson('/api/sign-in', signInPayload(['password' => 'wrong-password']))->assertUnauthorized();

    $this->assertGuest('web');
});

test('unknown email is rejected with the same message', function () {
    $this->postJson('/api/sign-in', signInPayload(['email' => 'nobody@example.com']))->assertUnauthorized();

    $this->assertGuest('web');
});

test('invalid fields are rejected', function (string $field, mixed $value) {
    createUser();

    $this->postJson('/api/sign-in', signInPayload([$field => $value]))->assertUnprocessable()
        ->assertJsonValidationErrors([$field]);

    $this->assertGuest('web');
})->with([
    'email missing' => ['email', null],
    'email invalid' => ['email', 'not-an-email'],
    'password missing' => ['password', null],
]);
