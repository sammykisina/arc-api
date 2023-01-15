<?php

declare(strict_types=1);

use Domains\Catalog\Models\Token;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

it('cannot return available tokens if current user is not executive', function (User $user): void {
    actingAs(user: $user)
      ->get(uri: route('api:v1:executive:tokens:index'))
        ->assertStatus(status: Http::CONFLICT());
})->with('user', 'token');

it('can return all available tokens', function (
    array $abilities,
    User $executive,
    Token $token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->get(uri: route('api:v1:executive:tokens:index'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
              ->where(key: 'id', expected: $token->id)
              ->where(key: 'type', expected: 'token')
              ->where(key: 'attributes.uuid', expected: $token->uuid)
              ->etc()
          )
        );
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'token');
