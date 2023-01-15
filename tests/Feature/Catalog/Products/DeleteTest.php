<?php

declare(strict_types=1);

use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a product if the current user is not super admin or admin', function (
    User $user,
    Product $product
) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:products:delete', $product->uuid))
        ->assertStatus(status: Http::CONFLICT());
})->with('user', 'product');

it('cannot delete a product with variants', function (
    User $admin,
    Product $product
) {
    actingAs(user: $admin, abilities:['admin']);
    Variant::factory()->create([
        'product_id' => $product->id,
    ]);

    delete(uri: route('api:v1:executive:products:delete', $product->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'product');

it('cannot delete a product with items in the store', function (
    array $abilities,
    Product $independent_product
) {
    $executive = User::factory()->executive()->create();
    actingAs(user: $executive, abilities: $abilities);

    delete(uri: route('api:v1:executive:products:delete', $independent_product->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'independent_product');

it('cannot delete a product linked to unassigned procurement', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_singles_product
) {
    actingAs(user: $executive, abilities: $abilities);
    $product = Product::query()->where('id', $procurement_with_singles_product->item_id)->first();

    delete(uri: route('api:v1:executive:products:delete', $product->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'procurement_with_singles_product');

it('can delete a product', function (
    array $abilities,
    User $executive,
    Product $independent_product_with_no_items_in_store
) {
    actingAs(user: $executive, abilities: $abilities);

    delete(uri: route('api:v1:executive:products:delete', $independent_product_with_no_items_in_store->uuid))
        ->assertStatus(status: Http::ACCEPTED());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'independent_product_with_no_items_in_store');

// cannot delete a product linked to unassigned token
