<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)->in('Feature');


function makeUser(array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'username' => 'john_doe',
        'email' => 'john@example.com',
        'password_hash' => Hash::make('old-password'),
    ], $overrides));
}

function storeToken(string $email, string $token = 'valid-token'): string
{
    Redis::setex("password-reset:email:{$email}", 300, $token);
    Redis::setex("password-reset:token:{$token}", 300, $email);

    return $token;
}

function resetPayload(array $overrides = []): array
{
    return array_merge([
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ], $overrides);
}
