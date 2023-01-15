<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Token;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;
use Illuminate\Support\Str;

it('cannot update the store quantity from token if current user is not executive', function (
    User $user,
    Token $approved_token
) {
    actingAs(user: $user)->patch(uri: route('api:v1:executive:tokens:update_store', $approved_token->uuid))
      ->assertStatus(status: Http::CONFLICT());
})->with('user', 'approved_token');

it('cannot update item store quantity with a token that does not exits', function (
    array $abilities,
    User $executive,
) {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(uri: route('api:v1:executive:tokens:update_store', Str::uuid()->toString()))
        ->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive');

it('can only update an item store quantity with an approved token', function (
    array $abilities,
    User $executive,
    Token $token
) {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(uri: route('api:v1:executive:tokens:update_store', $token->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'token');

it('will force the executive to create the token item if its missing before updating the store quantity', function (
    array $abilities,
    User $executive,
    Token $approved_admin_token_not_tired_to_item
) {
    actingAs(user: $executive, abilities: $abilities)
      ->patch(uri: route('api:v1:executive:tokens:update_store', $approved_admin_token_not_tired_to_item->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'approved_admin_token_not_tired_to_item');

it('can update the item quantity', function (
    array $abilities,
    User $executive,
    Variant $variant
) {
    $token = Token::factory()->approved()->create([
        'item_id' => $variant->id,
        'item_type' => AllowedItemTypes::VARIANT->value
    ]);

    actingAs(user: $executive, abilities: $abilities)
      ->patch(uri: route('api:v1:executive:tokens:update_store', $token->uuid))
        ->assertStatus(status: Http::ACCEPTED());

    $initial_store_quantity = $variant->store;
    $new_store_quantity = $variant->refresh()->store;
    expect(value: $new_store_quantity - $initial_store_quantity)
      ->toEqual(expected: $token->number_of_single_pieces);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'variant');
