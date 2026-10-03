<?php

use App\Models\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('returns a different array when user creates list', function () {
    Sanctum::actingAs(makeUser());

    $this->getJson('/api/lists')->assertOk()->assertJsonCount(0);

    $this->postJson('/api/lists', ['title' => 'test', 'description' => 'test'])->assertCreated()
        ->assertJsonStructure(['id', 'title', 'description']);

    $this->getJson('/api/lists')->assertOk()->assertJsonCount(1);
});

test('returns the expected json structure', function () {
    $user = makeUser();

    TaskList::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $this->postJson('/api/lists', ['title' => 'test', 'description' => 'test'])->assertCreated()
        ->assertJsonStructure(['id', 'title', 'description']);
});

test('requires authentication', function () {
    $this->getJson('/api/lists', ['title' => 'test', 'description' => 'test'])->assertUnauthorized();
});

test('returns 404 when user no longer exists', function () {
    $user = makeUser();

    Sanctum::actingAs($user);

    $user->delete();

    $this->postJson('/api/lists', ['title' => 'test', 'description' => 'test'])
        ->assertNotFound()
        ->assertExactJson(['message' => 'user not found']);
});
