<?php

declare(strict_types=1);

use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a procurement if the current user is not super admin or admin', function (
    User $user,
    Procurement $procurement
) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:procurements:delete', $procurement->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'procurement');

it('can only delete a procurement that exists', function (User $admin) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:procurements:delete', Str::uuid()->toString()))->assertNotFound();
})->with('admin', 'procurement');

it('cannot delete a pending procurement', function (User $admin, Procurement $pending_procurement) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:procurements:delete', $pending_procurement->uuid))->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'pending_procurement');

it('cannot delete a procurement if procured item is not yet added to store', function (
    User $admin,
    ProcurementItem $procurement_with_items_not_added_to_store
) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:procurements:delete', $procurement_with_items_not_added_to_store->procurement->uuid))
      ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'procurement_with_items_not_added_to_store');

it('can delete a procurement for super admin', function (
    User $super_admin,
    ProcurementItem $procurement_with_items_added_to_store
) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    delete(uri: route('api:v1:executive:procurements:delete', $procurement_with_items_added_to_store->procurement->uuid))
      ->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
         ->has(2)
         ->hasAll('error', 'message')
         ->where(key: 'error', expected: 0)
         ->where(key: 'message', expected: 'Procurement Deleted Successfully.')
         ->etc()
        );

    $this->assertDatabaseMissing('procurements', [
        'id' => $procurement_with_items_added_to_store->procurement->id,
        'uuid' => $procurement_with_items_added_to_store->procurement->uuid,
    ]);

    $this->assertDatabaseMissing('procurement_items', [
        'id' => $procurement_with_items_added_to_store->uuid,
        'uuid' => $procurement_with_items_added_to_store->id,
    ]);
})->with('super_admin', 'procurement_with_items_added_to_store');

it('can delete a procurement for admin', function (
    User $admin,
    ProcurementItem $procurement_with_items_added_to_store
) {
    actingAs(user: $admin, abilities:['admin']);

    delete(uri: route('api:v1:executive:procurements:delete', $procurement_with_items_added_to_store->procurement->uuid))
      ->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
         ->has(2)
         ->hasAll('error', 'message')
         ->where(key: 'error', expected: 0)
         ->where(key: 'message', expected: 'Procurement Deleted Successfully.')
         ->etc()
        );

    $this->assertDatabaseMissing('procurements', [
        'id' => $procurement_with_items_added_to_store->procurement->id,
        'uuid' => $procurement_with_items_added_to_store->procurement->uuid,
    ]);

    $this->assertDatabaseMissing('procurement_items', [
        'id' => $procurement_with_items_added_to_store->uuid,
        'uuid' => $procurement_with_items_added_to_store->id,
    ]);
})->with('admin', 'procurement_with_items_added_to_store');
