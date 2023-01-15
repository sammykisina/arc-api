<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Token;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

it('cannot allow update if the current user is not executive', function (
    User $user,
    Token $token
): void {
    actingAs(user: $user)->patch(uri: route('api:v1:executive:tokens:update', $token->uuid))
      ->assertStatus(status: Http::CONFLICT());
})->with('user', 'token');

it('cannot update a token name to another existing token name', function (
    array $abilities,
    User $executive,
    Token $token_being_updated,
    Token $exiting_token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token_being_updated->uuid),
          data: [
              'name' => $exiting_token->name
          ]
      )->assertSessionHasErrors(keys: ['name']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token', 'token');

it('validates the item_id if included', function (
    array $abilities,
    User $executive,
    Token $token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token->uuid),
          data: [
              'item_id' => "some item id"
          ]
      )->assertSessionHasErrors(keys: ['item_id']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');

it('validates the item_type if the item_id is included', function (
    array $abilities,
    User $executive,
    Token $token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token->uuid),
          data: [
              'item_id' => 9
          ]
      )->assertSessionHasErrors(keys: ['item_type']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');

it('cannot let admin approve a token', function (
    User $admin,
    Token $token
): void {
    actingAs(user: $admin, abilities: ['admin'])
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token->uuid),
          data: [
              'approved' => true
          ]
      )->assertUnprocessable();
})->with('admin', 'token');

it('ensures that the item is available', function (
    array $abilities,
    User $executive,
    Token $token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token->uuid),
          data: [
              'item_id' => 9,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertUnprocessable();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');

it('ensures that the target token is available', function (
    array $abilities,
    User $executive,
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', Str::uuid()->toString()),
          data: [
              'item_id' => 9,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive');

it('cannot allow manual update of added_to_store field', function (
    array $abilities,
    User $executive,
    Token $token
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(
          uri: route('api:v1:executive:tokens:update', $token->uuid),
          data: ['added_to_store' => true]
      )->assertUnprocessable();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');

it('allows super admin update a token', function (
    User $super_admin,
    Token $admin_token_not_tied_to_item,
    Variant $variant
): void {
    actingAs(user: $super_admin, abilities: ['super-admin'])
      ->patch(
          uri: route('api:v1:executive:tokens:update', $admin_token_not_tied_to_item->uuid),
          data: [
              'name' => 'new updated name',
              'item_id' => $variant->id,
              'item_type' => AllowedItemTypes::VARIANT->value,
              'approved' => true
          ]
      )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: "Token Updated Successfully.")
            ->etc()
            );
    $this->assertDatabaseHas('tokens', [
        'name' => 'new updated name',
        'item_id' => $variant->id
    ]);
})->with('super_admin', 'admin_token_not_tied_to_item', 'variant');

it('allows admin update a token', function (
    User $admin,
    Token $admin_token_not_tied_to_item,
    Variant $variant
): void {
    actingAs(user: $admin, abilities: ['admin'])
      ->patch(
          uri: route('api:v1:executive:tokens:update', $admin_token_not_tied_to_item->uuid),
          data: [
              'name' => 'new updated name',
              'item_id' => $variant->id,
              'item_type' => AllowedItemTypes::VARIANT->value,
          ]
      )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: "Token Updated Successfully.")
            ->etc()
            );
    $this->assertDatabaseHas('tokens', [
        'name' => 'new updated name',
        'item_id' => $variant->id
    ]);
})->with('admin', 'admin_token_not_tied_to_item', 'variant');

it('notifies the admin when super admin approved his/her token', function (): void {})->skip();
