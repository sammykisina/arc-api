<?php

declare(strict_types=1);

use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Supplier;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a supplier item if the current user is not super admin or admin', function (User $user, Supplier $supplier) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid))->assertStatus(Http::CONFLICT());
})->with('user', 'supplier');

it('validates that the correct type of the item is given', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities:['admin']);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'some type unknown',
        ]
    )->assertSessionHasErrors(keys: ['type']);
})->with('admin', 'supplier');

it('validates that the variant id exists', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities:['admin']);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'variant',
            'variant_id' => 15,
        ]
    )->assertSessionHasErrors(keys: ['variant_id']);
})->with('admin', 'supplier');

it('validates that the product id exists', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities:['admin']);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'product',
            'product_id' => 15,
        ]
    )->assertSessionHasErrors(keys: ['product_id']);
})->with('admin', 'supplier');

it('validates that all needed info is included when deleting a supply variant', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities:['admin']);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'variant',
        ]
    )->assertSessionHasErrors(keys: ['variant_id']);
})->with('admin', 'supplier');

it('validates that all needed info is included when deleting a supply product', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities:['admin']);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'product',
        ]
    )->assertSessionHasErrors(keys: ['product_id']);
})->with('admin', 'supplier');

it('can delete a supplier variant for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);
    $supplier = Supplier::factory()->create();
    $variant = Variant::factory()->create();
    $supplier->variants()->attach($variant->id);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'variant',
            'variant_id' => $variant->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Supply Variant Deleted Successfully.')
      ->etc()
      );

    $this->assertDatabaseMissing('supplier_variant', [
        'supplier_id' => $supplier->id,
        'variant_id' => $variant->id,
    ]);
})->with('super_admin');

it('can delete a supplier variant for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);
    $supplier = Supplier::factory()->create();
    $variant = Variant::factory()->create();
    $supplier->variants()->attach($variant->id);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'variant',
            'variant_id' => $variant->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Supply Variant Deleted Successfully.')
      ->etc()
      );

    $this->assertDatabaseMissing('supplier_variant', [
        'supplier_id' => $supplier->id,
        'variant_id' => $variant->id,
    ]);
})->with('admin');

it('can delete a supplier product for super admin', function (User $super_admin, Product $independent_product) {
    actingAs(user: $super_admin, abilities:['super-admin']);
    $supplier = Supplier::factory()->create();
    $supplier->products()->attach($independent_product->id);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'product',
            'product_id' => $independent_product->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Supply Product Deleted Successfully.')
      ->etc()
      );

    $this->assertDatabaseMissing('supplier_product', [
        'supplier_id' => $supplier->id,
        'product_id' => $independent_product->id,
    ]);
})->with('super_admin', 'independent_product');

it('can delete a supplier product for admin', function (User $admin, Product $independent_product) {
    actingAs(user: $admin, abilities:['admin']);
    $supplier = Supplier::factory()->create();
    $supplier->products()->attach($independent_product->id);

    delete(
        uri: route('api:v1:executive:suppliers:items_delete', $supplier->uuid),
        data: [
            'type' => 'product',
            'product_id' => $independent_product->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Supply Product Deleted Successfully.')
      ->etc()
      );

    $this->assertDatabaseMissing('supplier_product', [
        'supplier_id' => $supplier->id,
        'product_id' => $independent_product->id,
    ]);
})->with('admin', 'independent_product');

// can delete a supplier product for admin
