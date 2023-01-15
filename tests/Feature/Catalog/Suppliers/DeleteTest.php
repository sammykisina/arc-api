<?php

declare(strict_types=1);

use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a supplier if the current user is not super admin or admin', function (User $user, Supplier $supplier) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:suppliers:delete', $supplier->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'supplier');

it('cannot delete a supplier which does not exist', function (
    array $abilities,
    User $executive,
) {
    actingAs(user: $executive, abilities: $abilities)
        ->delete(uri: route('api:v1:executive:suppliers:delete', Str::uuid()->toString()))
            ->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('cannot delete a supplier who has a pending procurement', function (
    array $abilities,
    User $executive,
    Procurement $pending_procurement_for_a_specific_supplier
) {
    actingAs(user: $executive, abilities: $abilities)
        ->delete(uri: route('api:v1:executive:suppliers:delete', $pending_procurement_for_a_specific_supplier->supplier->uuid))
            ->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'pending_procurement_for_a_specific_supplier');

it('can delete supplier', function (
    array $abilities,
    User $executive,
    Supplier $supplier
) {
    actingAs(user: $executive, abilities: $abilities)
        ->delete(uri: route('api:v1:executive:suppliers:delete', $supplier->uuid))
             ->assertStatus(status: Http::ACCEPTED())
                ->assertJson(
                    fn (AssertableJson $json) => $json
                    ->has(2)
                    ->hasAll('error', 'message')
                    ->where(key: 'error', expected: 0)
                    ->where(key: 'message', expected: 'Supplier Deleted Successfully.')
                    ->etc()
                );
    $this->assertDatabaseMissing('suppliers', [
        'id' => $supplier->id,
        'uuid' => $supplier->uuid,
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'supplier');
