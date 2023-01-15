<?php

declare(strict_types=1);

use Domains\Fulfillment\Models\Table;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot return available tables if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:executive:tables:index'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can return available tables for super admin', function (User $super_admin, Table $table) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    get(uri: route('api:v1:executive:tables:index'))->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes')
            ->where(key: 'type', expected: 'table')
            ->where(key: 'id', expected: $table->id)
            ->where(key: 'attributes.uuid', expected: $table->uuid)
            ->etc()
          )
      );
})->with('super_admin', 'table');

it('can return available tables for admin', function (User $admin, Table $table) {
    actingAs(user: $admin, abilities: ['admin']);

    get(uri: route('api:v1:executive:tables:index'))->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes')
            ->where(key: 'type', expected: 'table')
            ->where(key: 'id', expected: $table->id)
            ->where(key: 'attributes.uuid', expected: $table->uuid)
            ->etc()
          )
      );
})->with('admin', 'table');
