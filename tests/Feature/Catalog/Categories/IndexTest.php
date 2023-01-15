<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('it cannot return available categories if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:executive:categories:index'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('it can return available categories for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: route('api:v1:executive:categories:index'))
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes')
          ->etc()
        )
      );
})->with('super_admin', 'category');

it('it can return available categories for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    get(uri: route('api:v1:executive:categories:index'))
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes')
          ->etc()
        )
      );
})->with('admin', 'category');

it('it can return available categories with their associated products for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: 'api/v1/executive/categories?include=products')
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.products')
          ->etc()
        )
      );
})->with('super_admin', 'category');

it('it can return available categories with their associated products for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    get(uri: 'api/v1/executive/categories?include=products')
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.products')
          ->etc()
        )
      );
})->with('admin', 'category');
