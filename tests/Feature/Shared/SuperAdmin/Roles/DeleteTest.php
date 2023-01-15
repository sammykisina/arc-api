<?php

declare(strict_types=1);

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a role if the current user is not the super admin', function (User $user, Role $role) {
    actingAs(user: $user);

    delete(route('api:v1:superadmin:roles:delete', $role->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'role');

it('cannot delete a role which does not exist', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    delete(route('api:v1:superadmin:roles:delete', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('super_admin');

it('cannot delete super admin role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $superAdminRole = Role::query()->where('slug', 'super-admin')->first();

    delete(route('api:v1:superadmin:roles:delete', $superAdminRole->uuid))
    ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('super_admin');

it('can delete a role', function (User $super_admin, Role $role) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    delete(route('api:v1:superadmin:roles:delete', $role->uuid))
    ->assertStatus(status: Http::ACCEPTED())->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Role Deleted Successfully.')
    );

    $this->assertDatabaseMissing('roles', [
        'name' => $role->name,
        'slug' => $role->slug,
    ]);
})->with('super_admin', 'role');
