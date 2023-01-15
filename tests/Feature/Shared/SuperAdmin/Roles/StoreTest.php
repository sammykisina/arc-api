<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

it('should not create a role if the current user is not super admin', function (User $user) {
    actingAs(user:$user)
        ->post(uri: route('api:v1:superadmin:roles:store'))
            ->assertStatus(status: Http::CONFLICT());
})->with('user');

it('validates the role info', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin'])
        ->post(uri: route('api:v1:superadmin:roles:store'))
            ->assertSessionHasErrors(keys: ['name', 'slug']);
})->with('super_admin');

it('can not create one role more than once', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin'])
        ->post(
            uri: route('api:v1:superadmin:roles:store'),
            data: ['name' => 'role name', 'slug' => 'role-name']
        );

    $this->post(
        uri: route('api:v1:superadmin:roles:store'),
        data: ['name' => 'role name', 'slug' => 'role-name']
    )->assertSessionHasErrors(keys: ['name', 'slug']);
})->with('super_admin');

it('can create a role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin'])
        ->post(
            uri: route('api:v1:superadmin:roles:store'),
            data: ['name' => 'role name', 'slug' => 'role-name']
        )->assertStatus(status: Http::CREATED())
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has(key: 3)
                    ->hasAll('error', 'message', 'role')
                    ->where(key: 'error', expected: 0)
                    ->where(key: 'message', expected: 'Role Created Successfully.')
                    ->has(
                        'role',
                        fn ($json) => $json
                        ->hasAll('id', 'type', 'attributes')
                        ->where(key: 'type', expected:'role')
                        ->where(key: 'attributes.name', expected: 'role name')
                        ->where(key: 'attributes.slug', expected: 'role-name')
                        ->etc()
                    )
            );
    $this->assertDatabaseHas('roles', [
        'name' => 'role name',
        'slug' => 'role-name',
    ]);
})->with('super_admin');
