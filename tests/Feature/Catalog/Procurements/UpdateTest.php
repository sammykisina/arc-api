<?php

declare(strict_types=1);

use Carbon\Carbon;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update a procurement if the current user is not super admin or admin', function (
    User $user,
    Procurement $procurement
) {
    actingAs(user: $user);

    patch(uri: route('api:v1:executive:procurements:update', $procurement->uuid))->assertStatus(Http::CONFLICT());
})->with('user', 'procurement');

it('ensures that the status is of the allowed types', function (
    User $admin,
    Procurement $pending_procurement
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $pending_procurement->uuid),
        data: [
            'status' => 'some unknown status',
        ]
    )->assertSessionHasErrors(keys: ['status']);
})->with('admin', 'pending_procurement');

it('ensures that the new supplier is available', function (
    User $admin,
    Procurement $pending_procurement,
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $pending_procurement->uuid),
        data: [
            'supplier_id' => 40,
        ]
    )->assertSessionHasErrors(keys: ['supplier_id']);
})->with('admin', 'pending_procurement');

it('validates the delivered date and total cost if status is delivered', function (
    User $admin,
    ProcurementItem $procurement_with_singles
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'procurement_uuid' => $procurement_with_singles->procurement->uuid,
        ]
    )->assertSessionHasErrors(keys: ['delivered_date', 'total_cost']);
})->with('admin', 'procurement_with_singles');

it('validates the number_of_items_in_form when status is delivered and procurement_item_form is either crate box or pack', function (
    User $admin,
    ProcurementItem $procurement_with_crate_box_pack
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_crate_box_pack->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'procurement_uuid' => $procurement_with_crate_box_pack->procurement->uuid,
        ]
    )->assertSessionHasErrors(keys: ['number_of_pieces_in_form']);
})->with('admin', 'procurement_with_crate_box_pack');

it('does not validate the number of items in the form when status is delivered and procurement item form is singles', function (
    User $admin,
    ProcurementItem $procurement_with_singles
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'procurement_uuid' => $procurement_with_singles->procurement->uuid,
        ]
    )->assertSessionDoesntHaveErrors(keys: ['number_of_pieces_in_form']);
})->with('admin', 'procurement_with_singles');

it('validates the cancelled date if status is cancelled', function (
    User $admin,
    Procurement $pending_procurement
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $pending_procurement->uuid),
        data: [
            'status' => ProcurementStatus::cancelled()->label,
        ]
    )->assertSessionHasErrors(keys: ['cancelled_date']);
})->with('admin', 'pending_procurement');

it('ensures that the procurement intended for update is available', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', Str::uuid()->toString()),
        data: [
            'status' => ProcurementStatus::cancelled()->label,
        ]
    )->assertNotFound();
})->with('admin');

it('ensures that the procurement intended for update is in pending status', function (
    User $admin,
    Procurement $delivered_procurement,
    Supplier $supplier
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $delivered_procurement->uuid),
        data: [
            'supplier_id' => $supplier->id,
            'status' => ProcurementStatus::cancelled()->label,
            'cancelled_date' => Carbon::now(),
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'delivered_procurement', 'supplier');

it('ensures that the new supplier is active for procurements', function (
    User $admin,
    Procurement $pending_procurement,
    Supplier $inactive_supplier,
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $pending_procurement->uuid),
        data: [
            'supplier_id' => $inactive_supplier->id,
            'status' => ProcurementStatus::cancelled()->label,
            'cancelled_date' => Carbon::now(),
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'pending_procurement', 'inactive_supplier');

it('ensures that the new supplier suppliers the procured item', function (
    User $admin,
    ProcurementItem $procurement_with_singles,
    Supplier $supplier,
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'supplier_id' => $supplier->id,
            'status' => ProcurementStatus::cancelled()->label,
            'cancelled_date' => Carbon::now(),
        ]
    )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with('admin', 'procurement_with_singles', 'supplier');

it('can send a cancellation email to the previous supplier', function (): void {})->skip();

it('can send a procurement email to the new supplier', function (): void {})->skip();

it('can update a procurement to cancelled state for super admin', function (
    User $super_admin,
    ProcurementItem $procurement_with_singles,
) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::cancelled()->label,
            'cancelled_date' => Carbon::now(),
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->has(2)
          ->hasAll('error', 'message')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Updated Successfully.')
      );

    expect(
        value: $procurement_with_singles->procurement->refresh()->status
    )->toEqual(expected: ProcurementStatus::cancelled()->label);
})->with('super_admin', 'procurement_with_singles');

it('can update a procurement to cancelled state for admin', function (
    User $admin,
    ProcurementItem $procurement_with_singles,
) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::cancelled()->label,
            'cancelled_date' => Carbon::now(),
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->has(2)
          ->hasAll('error', 'message')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Updated Successfully.')
      );

    expect(
        value: $procurement_with_singles->procurement->refresh()->status
    )->toEqual(expected: ProcurementStatus::cancelled()->label);
})->with('admin', 'procurement_with_singles');

it('can update a procurement with singles to delivered state for both super admin and admin', function (array $abilities) {
    $executive = User::factory()->executive()->create();
    $procurement_with_singles = ProcurementItem::factory()->singles()->create();
    actingAs(user: $executive, abilities: $abilities);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'delivered_date' => Carbon::now(),
            'procurement_uuid' => $procurement_with_singles->procurement->uuid,
            'total_cost' => 12000,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->has(2)
          ->hasAll('error', 'message')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Updated Successfully.')
      );

    $procurement_with_singles = $procurement_with_singles->procurement->refresh();
    expect(
        value: $procurement_with_singles->status
    )->toEqual(expected: ProcurementStatus::delivered()->label);

    expect(
        value: $procurement_with_singles->total_cost
    )->toEqual(expected: 12000);

    expect(
        value: $procurement_with_singles->item->number_of_pieces_in_form
    )->toBeNull();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
]);

it('can update a procurement with either crate box or pack form to delivered state for both super admin and admin', function (array $abilities) {
    $executive = User::factory()->executive()->create();
    $procurement_with_crate_box_pack = ProcurementItem::factory()->crate_box_pack()->create();
    actingAs(user: $executive, abilities: $abilities);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_crate_box_pack->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'delivered_date' => Carbon::now(),
            'procurement_uuid' => $procurement_with_crate_box_pack->procurement->uuid,
            'number_of_pieces_in_form' => 20,
            'total_cost' => 12000,
        ]
    )->assertStatus(status: Http::ACCEPTED())
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->has(2)
          ->hasAll('error', 'message')
          ->where(key: 'error', expected: 0)
          ->where(key: 'message', expected: 'Procurement Updated Successfully.')
      );

    $procurement_with_crate_box_pack = $procurement_with_crate_box_pack->procurement->refresh();
    expect(
        value: $procurement_with_crate_box_pack->status
    )->toEqual(expected: ProcurementStatus::delivered()->label);

    expect(
        value: $procurement_with_crate_box_pack->total_cost
    )->toEqual(expected: 12000);

    expect(
        value: $procurement_with_crate_box_pack->item->number_of_pieces_in_form
    )->toEqual(expected: 20);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
]);

it('can update the number of closed deals when the status is delivered', function (array $abilities) {
    $executive = User::factory()->executive()->create();
    $procurement_with_singles = ProcurementItem::factory()->singles()->create();
    actingAs(user: $executive, abilities: $abilities);

    patch(
        uri: route('api:v1:executive:procurements:update', $procurement_with_singles->procurement->uuid),
        data: [
            'status' => ProcurementStatus::delivered()->label,
            'delivered_date' => Carbon::now(),
            'procurement_uuid' => $procurement_with_singles->procurement->uuid,
            'total_cost' => 12000,
        ]
    )->assertStatus(status: Http::ACCEPTED());

    expect(
        value: $procurement_with_singles->procurement->refresh()->supplier->number_of_closed_deals
    )->toEqual(expected: 1);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
]);
