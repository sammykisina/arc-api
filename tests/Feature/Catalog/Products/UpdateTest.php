<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;
use Domains\Catalog\Models\Product;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update a product if the current user is not the super admin or the admin', function (User $user, Product $product) {
    actingAs(user: $user);

    patch(uri: route('api:v1:executive:products:update', $product->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'product');

it('cannot update a product that does not exists', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    patch(uri: route('api:v1:executive:products:update', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('admin');

it('cannot update a product name to another existing product name', function (User $admin, Product $independent_product) {
    actingAs(user: $admin, abilities:['admin']);

    patch(
        uri: route('api:v1:executive:products:update', $independent_product->uuid),
        data: [
            'name' => $independent_product->name,
            'measure' => $independent_product->measure,
        ]
    )->assertSessionHasErrors(keys: ['name']);
})->with('admin', 'product');

it('can update an independent product for super admin', function (User $super_admin, Product $independent_product, Category $category) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    patch(
        uri: route('api:v1:executive:products:update', $independent_product->uuid),
        data: [
            'name' => 'updated new product name',
            'cost' => 15,
            'retail' => 20,
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Product Updated Successfully.')
      );

    $this->assertDatabaseHas('products', [
        'name' => 'updated new product name',
        'cost' => 15,
        'retail' => 20,
        'category_id' => $category->id,
    ]);
})->with('super_admin', 'independent_product', 'category');

it('can update an independent product for admin', function (User $admin, Product $independent_product, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:products:update', $independent_product->uuid),
        data: [
            'name' => 'updated new product name',
            'cost' => 15,
            'retail' => 20,
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Product Updated Successfully.')
      );

    $this->assertDatabaseHas('products', [
        'name' => 'updated new product name',
        'cost' => 15,
        'retail' => 20,
        'category_id' => $category->id,
    ]);
})->with('admin', 'independent_product', 'category');

it('can update an dependent product for super admin', function (User $super_admin, Product $dependent_product, Category $category) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    patch(
        uri: route('api:v1:executive:products:update', $dependent_product->uuid),
        data: [
            'name' => 'updated new product name',
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Product Updated Successfully.')
      );

    $this->assertDatabaseHas('products', [
        'name' => 'updated new product name',
        'category_id' => $category->id,
    ]);
})->with('super_admin', 'dependent_product', 'category');

it('can update an dependent product for admin', function (User $admin, Product $dependent_product, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:products:update', $dependent_product->uuid),
        data: [
            'name' => 'updated new product name',
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Product Updated Successfully.')
      );

    $this->assertDatabaseHas('products', [
        'name' => 'updated new product name',
        'category_id' => $category->id,
    ]);
})->with('admin', 'dependent_product', 'category');
