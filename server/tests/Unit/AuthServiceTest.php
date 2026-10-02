<?php

use App\Mail\RecoverPasswordMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

uses(RefreshDatabase::class);
uses(Tests\TestCase::class);

beforeEach(function () {
    Redis::flushdb();
    Mail::fake();
});

afterEach(function () {
    Redis::flushdb();
});

test('sends reset link for an existing user', function () {
    makeUser();

    $this->postJson('/api/recover-password', ['email' => 'john@example.com'])->assertOk();

    Mail::assertSent(
        RecoverPasswordMail::class,
        fn(RecoverPasswordMail $mail) => $mail->hasTo('john@example.com')
    );
});

test('stores token and email keys in redis with ttl', function () {
    makeUser();

    $this->postJson('/api/recover-password', ['email' => 'john@example.com']);

    $token = Redis::get('password-reset:email:john@example.com');

    expect($token)->not->toBeNull()->and(Redis::get("password-reset:token:{$token}"))->toBe('john@example.com')
        ->and(Redis::ttl('password-reset:email:john@example.com'))->toBeGreaterThan(0)->toBeLessThanOrEqual(300)
        ->and(Redis::ttl("password-reset:token:{$token}"))->toBeGreaterThan(0)->toBeLessThanOrEqual(300);
});

test('email lookup is case insensitive', function () {
    makeUser();

    $this->postJson('/api/recover-password', ['email' => 'John@Example.com'])->assertOk();

    Mail::assertSent(RecoverPasswordMail::class, 1);
});

test('unknown email gets the same response and no mail', function () {
    $this->postJson('/api/recover-password', ['email' => 'nobody@example.com'])->assertOk();

    Mail::assertNothingSent();

    expect(Redis::keys('password-reset:*'))->toBeEmpty();
});

test('does not resend while a token is still valid', function () {
    makeUser();

    $this->postJson('/api/recover-password', ['email' => 'john@example.com']);

    $firstToken = Redis::get('password-reset:email:john@example.com');

    $this->postJson('/api/recover-password', ['email' => 'john@example.com'])->assertOk();

    Mail::assertSent(RecoverPasswordMail::class, 1);

    expect(Redis::get('password-reset:email:john@example.com'))->toBe($firstToken);
});

test('sends a new link after the previous token expired', function () {
    makeUser();

    $this->postJson('/api/recover-password', ['email' => 'john@example.com']);

    Redis::flushdb();

    $this->postJson('/api/recover-password', ['email' => 'john@example.com'])->assertOk();

    Mail::assertSent(RecoverPasswordMail::class, 2);
});

test('recover password rejects invalid email', function (mixed $email) {
    $this->postJson('/api/recover-password', ['email' => $email])->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    Mail::assertNothingSent();
})->with([
    'missing' => [null],
    'invalid' => ['not-an-email'],
    'too long' => [fn() => str_repeat('a', 250) . '@x.com'],
]);

test('user can reset password with a valid token', function () {
    $user = makeUser();

    $token = storeToken('john@example.com');

    $this->postJson("/api/reset-password/{$token}", resetPayload())->assertOk();

    expect(Hash::check('new-password', $user->fresh()->password_hash))->toBeTrue()
        ->and(Hash::check('old-password', $user->fresh()->password_hash))->toBeFalse();
});

test('token and email keys are removed after reset', function () {
    makeUser();

    $token = storeToken('john@example.com');

    $this->postJson("/api/reset-password/{$token}", resetPayload());

    expect(Redis::get("password-reset:token:{$token}"))->toBeNull()
        ->and(Redis::get('password-reset:email:john@example.com'))->toBeNull();
});

test('token cannot be used twice', function () {
    makeUser();
    $token = storeToken('john@example.com');

    $this->postJson("/api/reset-password/{$token}", resetPayload())->assertOk();

    $this->postJson("/api/reset-password/{$token}", resetPayload(['password' => 'another-pass', 'password_confirmation' => 'another-pass']))
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Token has expired');
});

test('unknown token is rejected', function () {
    $user = makeUser();

    $this->postJson('/api/reset-password/unknown-token', resetPayload())->assertUnauthorized()
        ->assertJsonPath('message', 'Token has expired');

    expect(Hash::check('old-password', $user->fresh()->password_hash))->toBeTrue();
});

test('token for a deleted user is rejected', function () {
    $user = makeUser();

    $token = storeToken('john@example.com');

    $user->delete();

    $this->postJson("/api/reset-password/{$token}", resetPayload())->assertUnauthorized()
        ->assertJsonPath('message', 'Token has expired');
});

test('reset password rejects invalid passwords', function (array $payload, string $field) {
    $user = makeUser();
    $token = storeToken('john@example.com');

    $this->postJson("/api/reset-password/{$token}", $payload)->assertUnprocessable()
        ->assertJsonValidationErrors([$field]);

    expect(Hash::check('old-password', $user->fresh()->password_hash))->toBeTrue()
        ->and(Redis::get("password-reset:token:{$token}"))->toBe('john@example.com');
})->with([
    'missing' => [[], 'password'],
    'too short' => [['password' => 'short', 'password_confirmation' => 'short'], 'password'],
    'confirmation mismatch' => [['password' => 'new-password', 'password_confirmation' => 'different'], 'password'],
    'confirmation missing' => [['password' => 'new-password'], 'password'],
]);
