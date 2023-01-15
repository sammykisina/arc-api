<?php

declare(strict_types=1);

use Domains\Fulfillment\Models\Table;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a table if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:tables:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('cannot create two or more tables with the same name', function (User $admin, Table $table) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => $table->name,
            'number_of_seats' => 2,
            'extendable' => false,
        ]
    )->assertSessionHasErrors(keys: ['name']);
})->with('admin', 'table');

it('validates the number of extending seats if extendable is true', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => 'table name',
            'number_of_seats' => 2,
            'extendable' => true,
        ]
    )->assertSessionHasErrors(keys:['number_of_extending_seats']);
})->with('admin');

it('can validate the table info for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(uri: route('api:v1:executive:tables:store'))
      ->assertSessionHasErrors(keys:['name', 'number_of_seats']);
})->with('super_admin');

it('can validate the table info for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(uri: route('api:v1:executive:tables:store'))
      ->assertSessionHasErrors(keys:['name', 'number_of_seats']);
})->with('admin');

it('can create a non extendable table for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => 'table name',
            'number_of_seats' => 2,
            'extendable' => false,
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
            ->has(key: 3)
            ->hasAll('error', 'message', 'table')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Table Created Successfully.')
            ->has(
                'table',
                fn ($json) => $json
              ->hasAll('id', 'type', 'attributes')
              ->where(key: 'type', expected:'table')
              ->where(key: 'attributes.name', expected: 'table name')
              ->where(key: 'attributes.number_of_extending_seats', expected: null)
              ->etc()
            ));
})->with('super_admin');

it('can create a non extendable table for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => 'table name',
            'number_of_seats' => 2,
            'extendable' => false,
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'table')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Table Created Successfully.')
          ->has(
              'table',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
            ->where(key: 'type', expected:'table')
            ->where(key: 'attributes.name', expected: 'table name')
            ->where(key: 'attributes.number_of_extending_seats', expected: null)
            ->etc()
          ));
})->with('admin');

it('can create an extendable table for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => 'table name',
            'number_of_seats' => 2,
            'extendable' => true,
            'number_of_extending_seats' => 2,
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'table')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Table Created Successfully.')
          ->has(
              'table',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
            ->where(key: 'type', expected:'table')
            ->where(key: 'attributes.name', expected: 'table name')
            ->where(key: 'attributes.number_of_extending_seats', expected: 2)
            ->etc()
          ));
})->with('super_admin');

it('can create an extendable table for admin', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    post(
        uri: route('api:v1:executive:tables:store'),
        data: [
            'name' => 'table name',
            'number_of_seats' => 2,
            'extendable' => true,
            'number_of_extending_seats' => 2,
        ]
    )->assertStatus(status: Http::CREATED())
    ->assertJson(fn (AssertableJson $json) => $json
          ->has(key: 3)
          ->hasAll('error', 'message', 'table')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Table Created Successfully.')
          ->has(
              'table',
              fn ($json) => $json
            ->hasAll('id', 'type', 'attributes')
            ->where(key: 'type', expected:'table')
            ->where(key: 'attributes.name', expected: 'table name')
            ->where(key: 'attributes.number_of_extending_seats', expected: 2)
            ->etc()
          ));
})->with('admin');
