<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;
use Illuminate\Testing\Fluent\AssertableJson;

it('cannot create a token if the current user is not executive', function (
    User $user
): void {
    actingAs(user: $user)
      ->post(uri: route('api:v1:executive:tokens:store'))
        ->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can validate new token data', function (
    array $abilities,
    User $executive
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(uri: route('api:v1:executive:tokens:store'))
        ->assertSessionHasErrors(keys: [
            'name',
            'number_of_single_pieces',
            'measure'
        ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('validates the item_id if included', function (
    array $abilities,
    User $executive
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: ['item_id' => "some unknown item_id"]
      )->assertSessionHasErrors(keys: ['item_id']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('validates the item_type if item_id is included', function (
    array $abilities,
    User $executive
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: ['item_id' => 5]
      )->assertSessionHasErrors(keys: ['item_type']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('validates the provided item_type is among the allowed types', function (
    array $abilities,
    User $executive
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'item_id' => 5,
              'item_type' => "some unknown item type"
          ]
      )->assertSessionHasErrors(keys: ['item_type']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('ensure that the token item is available', function (
    array $abilities,
    User $executive,
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => 250,
              'item_id' => 50,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('ensures that the token and the item if included are of the same measure', function (
    array $abilities,
    User $executive,
    Variant $variant
): void {
    actingAs(user: $executive, abilities: $abilities)
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => $variant->measure + 5,
              'item_id' => $variant->id,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertUnprocessable();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'variant');

it('can allow super admin to create an approved token tied to an item', function (
    User $super_admin,
    Variant $variant,
): void {
    actingAs(user: $super_admin, abilities: ['super-admin'])
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => $variant->measure,
              'item_id' => $variant->id,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertStatus(Http::CREATED())
        ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'token')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Token Created Successfully.')
            ->has(
                'token',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes')
              ->where(key: 'type', expected:'token')
              ->where(key: 'attributes.name', expected: 'token name')
              ->where(key: 'attributes.item_type', expected: AllowedItemTypes::VARIANT->value)
              ->etc()
            ));

    $this->assertDatabaseHas('tokens', [
        'name' => 'token name',
        'item_id' => $variant->id,
        'owner' => "Super Admin",
        'approved' => true
    ]);
})->with('super_admin', 'variant');

it('can allow super admin to create an approved token not tied to an item', function (User $super_admin): void {
    actingAs(user: $super_admin, abilities: ['super-admin'])
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => 250
          ]
      )->assertStatus(Http::CREATED())
        ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'token')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Token Created Successfully.')
            ->has(
                'token',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes')
              ->where(key: 'type', expected:'token')
              ->where(key: 'attributes.name', expected: 'token name')
              ->etc()
            ));

    $this->assertDatabaseHas('tokens', [
        'name' => 'token name',
        'item_id' => null,
        'owner' => "Super Admin",
        'approved' => true
    ]);
})->with('super_admin');

it('can allow admin to create unapproved token tied to an item', function (
    User $admin,
    Variant $variant,
): void {
    actingAs(user: $admin, abilities: ['admin'])
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => $variant->measure,
              'item_id' => $variant->id,
              'item_type' => AllowedItemTypes::VARIANT->value
          ]
      )->assertStatus(Http::CREATED())
        ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'token')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Token Created Successfully.')
            ->has(
                'token',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes')
              ->where(key: 'type', expected:'token')
              ->where(key: 'attributes.name', expected: 'token name')
              ->where(key: 'attributes.item_type', expected: AllowedItemTypes::VARIANT->value)
              ->etc()
            ));

    $this->assertDatabaseHas('tokens', [
        'name' => 'token name',
        'item_id' => $variant->id,
        'owner' => "Administrator",
        'approved' => false
    ]);
})->with('admin', 'variant');

it('can allow admin to create an approved token not tied to an item', function (User $super_admin): void {
    actingAs(user: $super_admin, abilities: ['super-admin'])
      ->post(
          uri: route('api:v1:executive:tokens:store'),
          data: [
              'name' => 'token name',
              'number_of_single_pieces' => 15,
              'measure' => 250
          ]
      )->assertStatus(Http::CREATED())
        ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'token')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Token Created Successfully.')
            ->has(
                'token',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes')
              ->where(key: 'type', expected:'token')
              ->where(key: 'attributes.name', expected: 'token name')
              ->etc()
            ));

    $this->assertDatabaseHas('tokens', [
        'name' => 'token name',
        'item_id' => null,
        'owner' => "Administrator",
        'approved' => false
    ]);
})->with('admin');

it('can notify the super admin when admin creates a token', function (): void {})->skip();
