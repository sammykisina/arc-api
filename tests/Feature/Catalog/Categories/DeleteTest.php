<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a category if current user is not the super admin or the admin', function (User $user, Category $category) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:categories:delete', $category->uuid))
    ->assertStatus(status: Http::CONFLICT());
})->with('user', 'category');

it('can delete a category for super admin', function (User $super_admin, Category $category) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    delete(route('api:v1:executive:categories:delete', $category->uuid))
    ->assertStatus(status: Http::ACCEPTED())->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Category Deleted Successfully.')
    );

    $this->assertDatabaseMissing('categories', [
        'uuid' => $category->uuid,
        'id' => $category->id,
    ]);
})->with('super_admin', 'category');

it('can delete a category for admin', function (User $admin, Category $category) {
    actingAs(user: $admin, abilities:['admin']);

    delete(route('api:v1:executive:categories:delete', $category->uuid))
    ->assertStatus(status: Http::ACCEPTED())->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Category Deleted Successfully.')
    );

    $this->assertDatabaseMissing('categories', [
        'name' => $category->name,
        'description' => $category->description,
    ]);
})->with('admin', 'category');

it('cannot delete a category that does not exits', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:categories:delete', Str::uuid()->toString()))
      ->assertStatus(status: Http::NOT_FOUND());
})->with('admin');
