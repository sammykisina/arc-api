<?php

declare(strict_types=1);

use Domains\Fulfillment\Models\Table;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update a table if the current user is not super admin or admin', function (User $user, Table $table) {
    actingAs(user: $user);

    patch(uri: route('api:v1:executive:tables:update', $table->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'table');

it('cannot update a table name to another existing table name', function (User $admin, Table $table) {
    actingAs(user: $admin, abilities: ['admin']);
    $another_table = Table::factory()->create();

    patch(
        uri: route('api:v1:executive:tables:update', $table->uuid),
        data: [
            'name' => $another_table->name,
        ]
    )->assertSessionHasErrors(keys: ['name']);
})->with('admin', 'table');

it('cannot update a table to extendable without the number of extending seats', function (
    array $abilities,
    User $executive,
    Table $non_extendable_table
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:tables:update', $non_extendable_table->uuid),
            data: [
                'extendable' => true,
            ]
        )->assertSessionHasErrors(keys: ['number_of_extending_seats']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'non_extendable_table');

it('can update a non extending table', function (
    array $abilities,
    User $executive,
    Table $table
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:tables:update', $table->uuid),
            data: [
                'name' => 'updated new table name',
                'number_of_seats' => 4,
            ]
        )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->has(2)
                ->hasAll('error', 'message')
                ->where(key: 'error', expected: 0)
                ->where(key: 'message', expected: 'Table Updated Successfully.')
            );
    $this->assertDatabaseHas('tables', [
        'name' => 'updated new table name',
        'number_of_seats' => 4,
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'table');

it('can update a non extending table to extendable', function (
    array $abilities,
    User $executive,
    Table $non_extendable_table
) {
    actingAs(user: $executive, abilities: $abilities)
        -> patch(
            uri: route('api:v1:executive:tables:update', $non_extendable_table->uuid),
            data: [
                'extendable' => true,
                'number_of_extending_seats' => 4,
            ]
        )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->has(key: 2)
                ->hasAll(key: ['error', 'message'])
                ->where(key: 'error', expected: 0)
                ->where(key: 'message', expected: 'Table Updated Successfully.')
            );
    $this->assertDatabaseHas('tables', [
        'extendable' => true,
        'number_of_extending_seats' => 4,
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'non_extendable_table');

it('can update a extending table to non extendable', function (
    array $abilities,
    User $executive,
    Table $extendable_table
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:tables:update', $extendable_table->uuid),
            data: ['extendable' => false]
        )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->has(2)
                ->hasAll('error', 'message')
                ->where(key: 'error', expected: 0)
                ->where(key: 'message', expected: 'Table Updated Successfully.')
            );
    $this->assertDatabaseHas('tables', [
        'extendable' => false,
        'number_of_extending_seats' => null,
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'extendable_table');
