<?php

declare(strict_types=1);

use Domains\Catalog\Models\Product;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot return available products if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri:route('api:v1:executive:products:index'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can return the available products for super admin', function (User $super_admin, Product $product) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    get(uri:route('api:v1:executive:products:index'))
    ->assertOk()
     ->assertJson(
         fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
         ->hasAll('type', 'attributes')
          ->where(key: 'type', expected: 'product')
          ->where(key: 'id', expected: $product->id)
          ->where(key: 'attributes.uuid', expected: $product->uuid)
          ->etc()
        )
     );
})->with('super_admin', 'product');

it('can return the available products for admin', function (User $admin, Product $product) {
    actingAs(user: $admin, abilities: ['admin']);

    get(uri:route('api:v1:executive:products:index'))
    ->assertOk()
     ->assertJson(
         fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes')
          ->where(key: 'type', expected: 'product')
          ->where(key: 'id', expected: $product->id)
          ->where(key: 'attributes.uuid', expected: $product->uuid)
          ->etc()
        )
     );
})->with('admin', 'product');

it('can return the available products for admin with its category ', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    get(uri:'api/v1/executive/products?include=category')
    ->assertOk()
     ->assertJson(
         fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.category')
          ->hasAll('relationships.category.type', 'relationships.category.attributes')
          ->etc()
        )
     );
})->with('super_admin', 'product');

it('can return the available products for admin with its variants', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    get(uri:'api/v1/executive/products?include=variants')
    ->assertOk()
     ->assertJson(
         fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.variants')
          ->etc()
        )
     );
})->with('super_admin', 'dependent_product');
