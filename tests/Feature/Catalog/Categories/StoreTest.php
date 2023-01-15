<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a new category if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:categories:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('validates the given category details for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(uri:route('api:v1:executive:categories:store'))->assertSessionHasErrors(['name', 'description']);
})->with('super_admin');

it('validates the given category details for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(uri:route('api:v1:executive:categories:store'))->assertSessionHasErrors(['name', 'description']);
})->with('admin');

it('create a new category for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(
        uri:route('api:v1:executive:categories:store'),
        data: [
            'name' => 'category name',
            'description' => 'category description',
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(key: 3)
      ->hasAll('error', 'message', 'category')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Category Created Successfully.')
      ->has(
          'category',
          fn ($json) => $json
        ->hasAll('id', 'type', 'attributes')
        ->where(key: 'type', expected:'category')
        ->where(key: 'attributes.name', expected: 'category name')
        ->where(key: 'attributes.description', expected: 'category description')
        ->etc()
      )
    );
})->with('super_admin');

it('create a new category for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri:route('api:v1:executive:categories:store'),
        data: [
            'name' => 'category name',
            'description' => 'category description',
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertStatus(status: Http::CREATED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(key: 3)
      ->hasAll('error', 'message', 'category')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Category Created Successfully.')
      ->has(
          'category',
          fn ($json) => $json
        ->hasAll('id', 'type', 'attributes')
        ->where(key: 'type', expected:'category')
        ->where(key: 'attributes.name', expected: 'category name')
        ->where(key: 'attributes.description', expected: 'category description')
        ->etc()
      )
    );
})->with('admin');

it('it cannot create two categories with the same name', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri:route('api:v1:executive:categories:store'),
        data: [
            'name' => $category->name,
            'description' => 'category description',
        ]
    )->assertSessionHasErrors('name');
})->with('super_admin', 'category');

// create a new category for admin
