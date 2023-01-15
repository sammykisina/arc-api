<?php

declare(strict_types=1);

use Domains\Catalog\Models\Token;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;
use Illuminate\Support\Str;

it('cannot delete a token if current user is not executive', function (
    User $user,
    Token $token
): void {
    actingAs(user: $user)->delete(uri: route('api:v1:executive:tokens:delete', $token->uuid))
      ->assertStatus(status: Http::CONFLICT());
})->with('user', 'token');

it('cannot delete a token which does not available', function (
    array $abilities,
    User $executive
) {
    actingAs(user: $executive, abilities: $abilities)
     ->delete(uri: route('api:v1:executive:tokens:delete', Str::uuid()->toString()))
       ->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive');

it('cannot delete an approved token whose items are not added to store', function (
    array $abilities,
    User $executive,
    Token $approved_token
) {
    actingAs(user: $executive, abilities: $abilities)
     ->delete(uri: route('api:v1:executive:tokens:delete', $approved_token->uuid))
       ->assertUnProcessable();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'approved_token');

it('can delete a token', function (
    array $abilities,
    User $executive,
    Token $token
) {
    actingAs(user: $executive, abilities: $abilities)
     ->delete(uri: route('api:v1:executive:tokens:delete', $token->uuid))
       ->assertStatus(status: Http::ACCEPTED());

    $this->assertDatabaseMissing('tokens', [
        'name' => $token->name,
        'uuid' => $token->uuid,
        'id' => $token->id
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');
