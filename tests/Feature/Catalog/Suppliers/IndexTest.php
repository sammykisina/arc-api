<?php

declare(strict_types=1);

use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot return available suppliers if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:executive:suppliers:index'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can return available suppliers for super admin', function (User $super_admin, Supplier $supplier_with_variant_and_product) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: 'api/v1/executive/suppliers?include=variants.product,products')->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes', 'relationships.variants', 'relationships.products')
            ->where(key: 'type', expected: 'supplier')
            ->where(key: 'id', expected: $supplier_with_variant_and_product->id)
            ->where(key: 'attributes.uuid', expected: $supplier_with_variant_and_product->uuid)
            ->etc()
          )
      );
})->with('super_admin', 'supplier_with_variant_and_product');

it('can return available suppliers for admin', function (User $admin, Supplier $supplier_with_variant_and_product) {
    actingAs(user: $admin, abilities:['admin']);

    get(uri: 'api/v1/executive/suppliers?include=variants.product,products')->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes')
            ->where(key: 'type', expected: 'supplier')
            ->where(key: 'id', expected: $supplier_with_variant_and_product->id)
            ->where(key: 'attributes.uuid', expected: $supplier_with_variant_and_product->uuid)
            ->etc()
          )
      );
})->with('admin', 'supplier_with_variant_and_product');
