<?php

declare(strict_types=1);

use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create supplier items if the current user is not super admin or admin', function (User $user, Supplier $supplier) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'supplier');

it('cannot create items for a supplier who does not exist', function (User $admin, Collection $four_variants) {
    actingAs(user: $admin, abilities: ['admin']);
    $variants = [];
    foreach ($four_variants as $variant) {
        array_push($variants, $variant->id);
    }

    post(
        uri: route('api:v1:executive:suppliers:items_store', Str::uuid()->toString()),
        data: [
            'variants' => $variants,
        ]
    )->assertStatus(status: Http::NOT_FOUND());
})->with('admin', 'four_variants');

it('validates the supplier variants', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'variants' => [1, 2, 3],
        ]
    )->assertSessionHasErrors(keys: ['variants']);
})->with('admin', 'supplier');

it('validates the supplier products', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'products' => [1, 2, 3],
        ]
    )->assertSessionHasErrors(keys: ['products']);
})->with('admin', 'supplier');

it('cannot create supplier variants or products if non provided', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities: $abilities)
        ->post(uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid))
            ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'supplier');

it('creates the supplier variants for super admin', function (User $super_admin, Supplier $supplier, Collection $four_variants) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $variants = [];
    foreach ($four_variants as $variant) {
        array_push($variants, $variant->id);
    }

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'variants' => $variants,
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(
          fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'supplier')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Supplier Items Created Successfully.')
            ->has(
                'supplier',
                fn ($json) => $json
                ->hasAll('id', 'type', 'attributes', 'relationships.variants', 'relationships.products')
                ->etc()
            )
      );
})->with('super_admin', 'supplier', 'four_variants');

it('creates the supplier products for super admin', function (User $super_admin, Supplier $supplier, Collection $four_independent_products) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $products = [];
    foreach ($four_independent_products as $product) {
        array_push($products, $product->id);
    }

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'products' => $products,
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'supplier')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Supplier Items Created Successfully.')
            ->has(
                'supplier',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.variants', 'relationships.products')
              ->etc()
            ));
})->with('super_admin', 'supplier', 'four_independent_products');

it('creates the supplier variants  for admin', function (User $admin, Supplier $supplier, Collection $four_variants) {
    actingAs(user: $admin, abilities: ['admin']);
    $variants = [];
    foreach ($four_variants as $variant) {
        array_push($variants, $variant->id);
    }

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'variants' => $variants,
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'supplier')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Supplier Items Created Successfully.')
            ->has(
                'supplier',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.variants', 'relationships.products')
              ->etc()
            ));
})->with('admin', 'supplier', 'four_variants');

it('creates the supplier products for admin', function (User $admin, Supplier $supplier, Collection $four_independent_products) {
    actingAs(user: $admin, abilities: ['admin']);
    $products = [];
    foreach ($four_independent_products as $product) {
        array_push($products, $product->id);
    }

    post(
        uri: route('api:v1:executive:suppliers:items_store', $supplier->uuid),
        data: [
            'products' => $products,
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'supplier')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Supplier Items Created Successfully.')
            ->has(
                'supplier',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.variants', 'relationships.products')
              ->etc()
            ));
})->with('admin', 'supplier', 'four_independent_products');
