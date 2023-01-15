<?php

declare(strict_types=1);

use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a variant if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:variants:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('cannot create a variant to a product that does exists', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(uri: route('api:v1:executive:variants:store'))->assertSessionHasErrors(keys: ['product_id']);
})->with('admin');

it('can validate all required variant info', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(uri: route('api:v1:executive:variants:store'))->assertSessionHasErrors(keys: ['name', 'cost', 'retail', 'stock', 'measure', 'product_id', 'vat']);
})->with('admin');

it('cannot create two variants with the same name and measure', function (User $admin, Product $dependent_product, Variant $variant) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri: route('api:v1:executive:variants:store'),
        data: [
            'name' => $variant->name,
            'cost' => 250,
            'retail' => 300,
            'stock' => 20,
            'measure' => 500,
            'product_id' => $dependent_product->id,
            'measure' => $variant->measure,
            'vat' => true,
        ]
    )->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('admin', 'dependent_product', 'variant');

it('can create a variant for super admin', function (User $super_admin, Product $dependent_product) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(
        uri: route('api:v1:executive:variants:store'),
        data: [
            'name' => 'variant name',
            'cost' => 250,
            'retail' => 300,
            'stock' => 20,
            'measure' => 500,
            'product_id' => $dependent_product->id,
            'measure' => 250,
            'vat' => true,
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'variant')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Variant Created Successfully.')
          ->has(
              'variant',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
            ->where(key: 'type', expected:'variant')
            ->where(key: 'attributes.name', expected: 'variant name')
            ->etc()
          ));

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('variants', [
              'name' => 'variant name',
              'product_id' => $dependent_product->id,
          ]);
})->with('super_admin', 'dependent_product');

it('can create a variant for admin', function (User $admin, Product $dependent_product) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri: route('api:v1:executive:variants:store'),
        data: [
            'name' => 'variant name',
            'cost' => 250,
            'retail' => 300,
            'stock' => 20,
            'measure' => 500,
            'product_id' => $dependent_product->id,
            'measure' => 250,
            'vat' => true,
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'variant')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Variant Created Successfully.')
          ->has(
              'variant',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
            ->where(key: 'type', expected:'variant')
            ->where(key: 'attributes.name', expected: 'variant name')
            ->etc()
          ));

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('variants', [
              'name' => 'variant name',
              'product_id' => $dependent_product->id,
          ]);
})->with('admin', 'dependent_product');
