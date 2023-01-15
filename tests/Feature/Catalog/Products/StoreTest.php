<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;
use Domains\Catalog\Models\Product;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('it cannot create a product if the current user is not super or admin', function (User $user) {
    actingAs(user: $user);

    post(uri:route('api:v1:executive:products:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('it validate the product details for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri:route('api:v1:executive:products:store'),
    )->assertSessionHasErrors(keys: ['name', 'form', 'category_id']);
})->with('super_admin');

it('it validate the product details for admin', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri:route('api:v1:executive:products:store'),
    )->assertSessionHasErrors(keys: ['name', 'form', 'category_id']);
})->with('admin');

it('it validate the independent product details for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'form' => 'independent',
        ]
    )->assertSessionHasErrors(keys: ['name', 'category_id', 'cost', 'retail', 'stock', 'measure', 'vat']);
})->with('super_admin');

it('it validate the independent product details for admin', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'form' => 'independent',
        ]
    )->assertSessionHasErrors(keys: ['name', 'category_id', 'cost', 'retail', 'stock', 'measure', 'vat']);
})->with('admin');

it('it validate the dependent product details for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'form' => 'dependent',
        ]
    )->assertSessionHasErrors(keys: ['name', 'category_id']);
})->with('super_admin');

it('it validate the dependent product details for admin', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'form' => 'dependent',
        ]
    )->assertSessionHasErrors(keys: ['name', 'category_id']);
})->with('admin');

it('it can create and return an independent product for super admin', function (User $super_admin, Category $category) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => 'product name',
            'form' => 'independent',
            'category_id' => $category->id,
            'cost' => 50,
            'retail' => 60,
            'stock' => 20,
            'measure' => 250,
            'vat' => true,
        ]
    )->assertStatus(status: Http::CREATED())
       ->assertJson(
           fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'product')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Product Created Successfully.')
            ->has(
                'product',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.category', 'relationships.variants')
              ->where(key: 'type', expected:'product')
              ->where(key: 'attributes.name', expected: 'product name')
              ->where(key: 'attributes.form', expected: 'independent')
              ->where(key: 'relationships.category.id', expected: $category->id)
              ->where(key: 'relationships.category.attributes.uuid', expected: $category->uuid)
              ->where(key: 'relationships.variants', expected: [])
              ->etc()
            )
       );

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('products', [
              'name' => 'product name',
              'category_id' => $category->id,
              'form' => 'independent',
          ]);
})->with('super_admin', 'category');

it('it can create and return an independent product for admin', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => 'product name',
            'form' => 'independent',
            'category_id' => $category->id,
            'cost' => 50,
            'retail' => 60,
            'stock' => 20,
            'measure' => 250,
            'vat' => true,
        ]
    )->assertStatus(status: Http::CREATED())
       ->assertJson(
           fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'product')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Product Created Successfully.')
            ->has(
                'product',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.category', 'relationships.variants')
              ->where(key: 'type', expected:'product')
              ->where(key: 'attributes.name', expected: 'product name')
              ->where(key: 'attributes.form', expected: 'independent')
              ->where(key: 'relationships.category.id', expected: $category->id)
              ->where(key: 'relationships.category.attributes.uuid', expected: $category->uuid)
              ->where(key: 'relationships.variants', expected: [])
              ->etc()
            )
       );

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('products', [
              'name' => 'product name',
              'category_id' => $category->id,
              'form' => 'independent',
          ]);
})->with('admin', 'category');

it('it can create and return an dependent product for super admin', function (User $super_admin, Category $category) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => 'product name',
            'form' => 'dependent',
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::CREATED())
       ->assertJson(
           fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'product')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Product Created Successfully.')
            ->has(
                'product',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.category', 'relationships.variants')
              ->where(key: 'type', expected:'product')
              ->where(key: 'attributes.name', expected: 'product name')
              ->where(key: 'attributes.form', expected: 'dependent')
              ->where(key: 'relationships.category.id', expected: $category->id)
              ->where(key: 'relationships.category.attributes.uuid', expected: $category->uuid)
              ->where(key: 'relationships.variants', expected: [])
              ->etc()
            )
       );

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('products', [
              'name' => 'product name',
              'category_id' => $category->id,
              'form' => 'dependent',
              'cost' => null,
              'store' => null,
          ]);
})->with('super_admin', 'category');

it('it can create and return an dependent product for admin', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => 'product name',
            'form' => 'dependent',
            'category_id' => $category->id,
        ]
    )->assertStatus(status: Http::CREATED())
       ->assertJson(
           fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'product')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Product Created Successfully.')
            ->has(
                'product',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes', 'relationships.category', 'relationships.variants')
              ->where(key: 'type', expected:'product')
              ->where(key: 'attributes.name', expected: 'product name')
              ->where(key: 'attributes.form', expected: 'dependent')
              ->where(key: 'relationships.category.id', expected: $category->id)
              ->where(key: 'relationships.category.attributes.uuid', expected: $category->uuid)
              ->where(key: 'relationships.variants', expected: [])
              ->etc()
            )
       );

    $this->assertDatabaseCount(table: 'products', count: 1)
          ->assertDatabaseHas('products', [
              'name' => 'product name',
              'category_id' => $category->id,
              'form' => 'dependent',
              'cost' => null,
              'store' => null,
          ]);
})->with('admin', 'category');

it('cannot create two independent products with the same name and measure', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);
    $product = Product::factory()->create([
        'name' => 'product name',
        'form' => 'independent',
        'category_id' => $category->id,
        'cost' => 50,
        'retail' => 60,
        'stock' => 20,
        'store' => 20,
        'measure' => 250,
        'vat' => true,
    ]);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => $product->name,
            'form' => 'independent',
            'category_id' => $category->id,
            'cost' => 50,
            'retail' => 60,
            'stock' => 20,
            'measure' => 250,
            'vat' => true,
        ]
    )->assertStatus(status:Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'category');

it('cannot create two dependent products with the same name', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities: ['admin']);
    $product = Product::factory()->create([
        'name' => 'product name',
        'form' => 'dependent',
        'category_id' => $category->id,
    ]);

    post(
        uri:route('api:v1:executive:products:store'),
        data:[
            'name' => $product->name,
            'form' => 'dependent',
            'category_id' => $category->id,
        ]
    )->assertStatus(status:Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'category');
