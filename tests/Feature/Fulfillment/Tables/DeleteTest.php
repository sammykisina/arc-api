<?php

declare(strict_types=1);

use Domains\Fulfillment\Models\Table;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a table if the current user is not super admin or admin', function (User $user, Table $table) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:tables:delete', $table->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'table');

it('cannot delete a table that does not exits', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:tables:delete', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('admin');

it('can delete a table for super admin', function (User $super_admin, Table $table) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    delete(uri: route('api:v1:executive:tables:delete', $table->uuid))
    ->assertStatus(status: Http::ACCEPTED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Table Deleted Successfully.')
    );

    $this->assertDatabaseMissing('tables', [
        'id' => $table->id,
        'uuid' => $table->uuid,
    ]);
})->with('super_admin', 'table');

it('can delete a table for admin', function (User $admin, Table $table) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:tables:delete', $table->uuid))
    ->assertStatus(status: Http::ACCEPTED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Table Deleted Successfully.')
    );

    $this->assertDatabaseMissing('tables', [
        'id' => $table->id,
        'uuid' => $table->uuid,
    ]);
})->with('admin', 'table');

it('cannot delete a table with pending orders', function () {})->skip();
