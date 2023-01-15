<?php

declare(strict_types=1);

use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

it('cannot update a procurement item if the current user is not super admin or admin', function (
    User $user,
    ProcurementItem $procurement_with_crate_box_pack
) {
    actingAs(user: $user)
        ->patch(uri: route('api:v1:executive:procurements:update_procurement_item', $procurement_with_crate_box_pack->procurement->uuid))
            ->assertStatus(status: Http::CONFLICT());
})->with('user', 'procurement_with_crate_box_pack');

it('ensures that the form is of the allowed forms when updating form', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_crate_box_pack
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $procurement_with_crate_box_pack->procurement->uuid),
            data: ['form' => 'some unknown form']
        )->assertSessionHasErrors(keys: ['form']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'procurement_with_crate_box_pack');

it('ensures that form quantity is provided when updating the form to either create box_pack', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_crate_box_pack
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $procurement_with_crate_box_pack->procurement->uuid),
            data: ['form' => ProcurementItemForms::crate()->label]
        )->assertSessionHasErrors(keys: ['form_quantity']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'procurement_with_crate_box_pack');

it('ensures that number_of_single_pieces is available when updating the form to singles', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_crate_box_pack
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $procurement_with_crate_box_pack->procurement->uuid),
            data: ['form' => ProcurementItemForms::singles()->label,]
        )->assertSessionHasErrors(keys: ['number_of_single_pieces']);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'procurement_with_crate_box_pack');

it('cannot update a procurement item of a procurement that does not exists', function (
    array $abilities,
    User $executive,
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', Str::uuid()->toString())
        )->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive');

it('cannot update a procurement item of a delivered procurement', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_items_added_to_store
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $procurement_with_items_added_to_store->procurement->uuid)
        )->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']]
], 'executive', 'procurement_with_items_added_to_store');

it('can update a procurement item to singles form', function (
    array $abilities,
    User $executive,
    ProcurementItem $pending_procurement_with_items_not_added_to_store
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $pending_procurement_with_items_not_added_to_store->procurement->uuid),
            data: [
                'form' => ProcurementItemForms::singles()->label,
                'number_of_single_pieces' => 20,
            ]
        )->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
            ->has(2)
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Procurement Item Updated Successfully.')
            ->etc()
        );

    $pending_procurement_with_items_not_added_to_store = $pending_procurement_with_items_not_added_to_store->refresh();
    expect(value: $pending_procurement_with_items_not_added_to_store->form)
        ->toEqual(expected: ProcurementItemForms::singles()->label);
    expect(value: $pending_procurement_with_items_not_added_to_store->number_of_single_pieces)
        ->toEqual(expected: 20);
    expect(value: $pending_procurement_with_items_not_added_to_store->form_quantity)
        ->toBeNull();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'pending_procurement_with_items_not_added_to_store');

it('can update a procurement item to either crate box or pack form', function (
    array $abilities,
    User $executive,
    ProcurementItem $pending_procurement_with_items_not_added_to_store
) {
    actingAs(user: $executive, abilities: $abilities)
        ->patch(
            uri: route('api:v1:executive:procurements:update_procurement_item', $pending_procurement_with_items_not_added_to_store->procurement->uuid),
            data: [
                'form' => ProcurementItemForms::pack()->label,
                'form_quantity' => 5,
            ]
        )->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
            ->has(2)
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Procurement Item Updated Successfully.')
            ->etc()
        );

    $pending_procurement_with_items_not_added_to_store = $pending_procurement_with_items_not_added_to_store->refresh();
    expect(value: $pending_procurement_with_items_not_added_to_store->form)
      ->toEqual(expected: ProcurementItemForms::pack()->label);
    expect(value: $pending_procurement_with_items_not_added_to_store->number_of_single_pieces)
      ->toBeNull();
    expect(value: $pending_procurement_with_items_not_added_to_store->form_quantity)
      ->toEqual(expected: 5);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'pending_procurement_with_items_not_added_to_store');
