<?php

declare(strict_types=1);

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('should not update a role if the current user is not the super admin', function (User $user, Role $role) {
    actingAs(user: $user);
    patch(route('api:v1:superadmin:roles:update', $role->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'role');

it('cannot update missing role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    patch(route('api:v1:superadmin:roles:update', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('super_admin', 'role');

it('cannot edit Super Admin role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $superAdminRole = Role::query()->where('slug', 'super-admin')->first();

    patch(
        uri: route('api:v1:superadmin:roles:update', $superAdminRole->uuid),
        data: ['slug' => 'some-slug']
    )->assertStatus(status: Http::NOT_MODIFIED());
})->with('super_admin');

it('cannot edit Admin role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    $adminRole = Role::query()->where('slug', 'admin')->first();

    patch(
        uri: route('api:v1:superadmin:roles:update', $adminRole->uuid),
        data: ['slug' => 'some-slug']
    )->assertStatus(status: Http::NOT_MODIFIED());
})->with('super_admin');

it('it can update role details', function (User $super_admin, Role $role) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    patch(
        uri: route('api:v1:superadmin:roles:update', $role->uuid),
        data: ['slug' => 'some-slug']
    )->assertStatus(status: Http::ACCEPTED())
     ->assertJson(
         fn (AssertableJson $json) => $json
        ->has(2)
        ->hasAll('error', 'message')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Role Updated Successfully.')
     );

    $this->assertDatabaseHas('roles', [
        'name' => $role->name,
        'slug' => 'some-slug',
    ]);
})->with('super_admin', 'role');

it('should not update a role to an existing role name and slug', function (User $super_admin, Role $role) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    patch(
        uri: route('api:v1:superadmin:roles:update', $role->uuid),
        data: ['name' => $role->name, 'slug' => $role->slug]
    )->assertSessionHasErrors('name', 'slug');
})->with('super_admin', 'role');
