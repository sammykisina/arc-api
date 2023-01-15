<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update the category info if the current user is not super admin or admin', function (User $user, Category $category) {
    actingAs(user: $user);

    patch(
        uri: route('api:v1:executive:categories:update', $category->uuid)
    )->assertStatus(status: Http::CONFLICT());
})->with('user', 'category');

it('can update category info for super admin', function (User $super_admin, Category $category) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    patch(
        uri: route('api:v1:executive:categories:update', $category->uuid),
        data: [
            'name' => 'updated new category name',
            'description' => 'updated new category description',
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Category Updated Successfully.')
      );

    $this->assertDatabaseHas('categories', [
        'name' => 'updated new category name',
        'description' => 'updated new category description',
    ]);
})->with('super_admin', 'category');

it('can update category info for admin', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities:['admin']);

    patch(
        uri: route('api:v1:executive:categories:update', $category->uuid),
        data: [
            'name' => 'updated new category name',
            'description' => 'updated new category description',
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Category Updated Successfully.')
      );

    $this->assertDatabaseHas('categories', [
        'name' => 'updated new category name',
        'description' => 'updated new category description',
    ]);
})->with('admin', 'category');

it('cannot update a category that does not exists', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    patch(
        uri: route('api:v1:executive:categories:update', Str::uuid()->toString()),
        data: [
            'name' => 'updated new category name',
            'description' => 'updated new category description',
        ]
    )->assertStatus(status: Http::NOT_FOUND());
})->with('admin');

it('cannot update category name to an existing category name', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities:['admin']);

    patch(
        uri: route('api:v1:executive:categories:update', $category->uuid),
        data: [
            'name' => $category->name,
        ]
    )->assertSessionHasErrors('name');
})->with('admin', 'category');
