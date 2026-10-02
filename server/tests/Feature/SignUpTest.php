<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function validPayload(array $overrides = []): array
{
    return array_merge([
        'username' => 'john_doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ], $overrides);
}

test('user can sign up', function () {
    $this->postJson('/api/sign-up', validPayload())->assertCreated()->assertJsonPath('username', 'john_doe')
        ->assertJsonPath('email', 'john@example.com')
        ->assertJsonMissingPath('password_hash');

    $this->assertDatabaseHas('users', [
        'username' => 'john_doe',
        'email' => 'john@example.com',
    ]);
});

test('password is stored hashed', function () {
    $this->postJson('/api/sign-up', validPayload());

    $user = User::where('email', 'john@example.com')->firstOrFail();

    expect($user->password_hash)
        ->not->toBe('secret123')
        ->toHaveLength(60)
        ->and(Hash::check('secret123', $user->password_hash))->toBeTrue();
});

test('user is authenticated after sign up', function () {
    $this->postJson('/api/sign-up', validPayload());

    $this->assertAuthenticated('web');
});

test('email is stored in lowercase', function () {
    $this->postJson('/api/sign-up', validPayload(['email' => 'John@Example.com']));

    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('id is a uuid', function () {
    $response = $this->postJson('/api/sign-up', validPayload());

    expect(Str::isUuid($response->json('id')))->toBeTrue();
});

test('username must be unique', function () {
    User::factory()->create(['username' => 'john_doe']);

    $this->postJson('/api/sign-up', validPayload())->assertUnprocessable()->assertJsonValidationErrors(['username']);
});

test('email must be unique', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $this->postJson('/api/sign-up', validPayload())->assertUnprocessable()->assertJsonValidationErrors(['email']);
});

test('password confirmation must match', function () {
    $this->postJson('/api/sign-up', validPayload(['password_confirmation' => 'different']))->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

test('invalid fields are rejected', function (string $field, mixed $value) {
    $this->postJson('/api/sign-up', validPayload([$field => $value]))->assertUnprocessable()
        ->assertJsonValidationErrors([$field]);

    $this->assertDatabaseCount('users', 0);
})->with([
    'username missing' => ['username', null],
    'username too short' => ['username', 'ab'],
    'username too long' => ['username', fn() => str_repeat('a', 17)],
    'username invalid chars' => ['username', 'john doe!'],
    'email missing' => ['email', null],
    'email invalid' => ['email', 'not-an-email'],
    'email too long' => ['email', fn() => str_repeat('a', 250) . '@x.com'],
    'password missing' => ['password', null],
    'password too short' => ['password', 'short'],
]);
