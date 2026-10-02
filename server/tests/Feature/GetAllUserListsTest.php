<?php

use App\Models\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('returns only the authenticated user lists', function () {
    $user = makeUser();

    $other = makeUser(['email' => 'other@example.com', 'username' => 'other_user']);

    TaskList::factory()->count(3)->create(['user_id' => $user->id]);

    TaskList::factory()->count(2)->create(['user_id' => $other->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/lists')->assertOk()->assertJsonCount(3);

    expect(collect($response->json())->pluck('id')->all())
        ->toEqualCanonicalizing($user->lists()->pluck('id')->all());
});

test('returns an empty array when user has no lists', function () {
    Sanctum::actingAs(makeUser());

    $this->getJson('/api/lists')->assertOk()->assertExactJson([]);
});

test('returns the expected json structure', function () {
    $user = makeUser();

    TaskList::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $this->getJson('/api/lists')->assertOk()
        ->assertJsonStructure([
            '*' => ['id', 'title', 'description'],
        ]);
});

test('requires authentication', function () {
    $this->getJson('/api/lists')->assertUnauthorized();
});

test('returns 404 when user no longer exists', function () {
    $user = makeUser();

    Sanctum::actingAs($user);

    $user->delete();

    $this->getJson('/api/lists')->assertNotFound()->assertExactJson(['message' => 'user not found']);
});
