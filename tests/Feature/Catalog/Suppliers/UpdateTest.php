<?php

declare(strict_types=1);

use Domains\Catalog\Constants\SuppliersStatus;
use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

it('cannot update a supplier if the current user is not super admin or admin', function (
    User $user,
    Supplier $supplier
) {
    actingAs(user: $user)->patch(uri: route('api:v1:executive:suppliers:update', $supplier->uuid))
        ->assertStatus(status: Http::CONFLICT());
})->with('user', 'supplier');

it('ensures that the supplier name is unique', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities:$abilities)
        ->patch(
            uri: route('api:v1:executive:suppliers:update', $supplier->uuid),
            data: ['name' => $supplier->name]
        )->assertSessionHasErrors(keys: ['name']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'supplier');

it('ensures that the email is unique', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities:$abilities)
        ->patch(
            uri: route('api:v1:executive:suppliers:update', $supplier->uuid),
            data: ['email' => $supplier->email]
        )->assertSessionHasErrors(keys: ['email']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'supplier');

it('ensures that the intended update_status is among the allowed statuses', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities:$abilities)
        ->patch(
            uri: route('api:v1:executive:suppliers:update', $supplier->uuid),
            data: ['status' => 'some unknown status']
        )->assertSessionHasErrors(keys: ['status']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'supplier');

it('ensures supplier being edited is available', function (
    array $abilities,
    User $executive,
) {
    actingAs(user: $executive, abilities:$abilities)
        ->patch(uri: route('api:v1:executive:suppliers:update', Str::uuid()->toString()))
            ->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('updates the supplier', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities:$abilities)
        ->patch(
            uri: route('api:v1:executive:suppliers:update', $supplier->uuid),
            data: [
                'name' => 'updated new name',
                'status' => SuppliersStatus::active()->label,
                'email' => 'some_new_email@gmail.com',
            ]
        )->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->has(2)
                ->hasAll('error', 'message')
                ->where(key: 'error', expected: 0)
                ->where(key: 'message', expected: 'Supplier Updated Successfully.')
                ->etc()
            );
    $this->assertDatabaseHas('suppliers', [
        'name' => 'Updated new name',
        'status' => SuppliersStatus::active()->label,
        'email' => 'some_new_email@gmail.com',
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'supplier');
