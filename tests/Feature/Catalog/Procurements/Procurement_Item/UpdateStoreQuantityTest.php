<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use illuminate\Support\Str;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update the store quantity from procurement if current user is not super admin or admin', function (
    User $user,
    ProcurementItem $procurement_with_items_not_added_to_store
) {
    actingAs(user: $user);

    patch(uri: route('api:v1:executive:procurements:update_store', $procurement_with_items_not_added_to_store->procurement->uuid))
        ->assertStatus(status: Http::CONFLICT());
})->with('user', 'procurement_with_items_not_added_to_store');

it('cannot update item store quantity with a procurement that does not exits', function (
    array $abilities,
    User $executive,
) {
    actingAs(user: $executive, abilities: $abilities);

    patch(uri: route('api:v1:executive:procurements:update_store', Str::uuid()->toString()))
        ->assertNotFound();
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive');

it('can only update an item store quantity with a delivered procurement', function (
    array $abilities,
    User $executive,
    Procurement $pending_procurement
) {
    actingAs(user: $executive, abilities: $abilities);

    patch(uri: route('api:v1:executive:procurements:update_store', $pending_procurement->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'pending_procurement');

it('can update the store quantity from a procurement of singles form', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_singles_and_items_not_added_to_store
) {
    actingAs(user: $executive, abilities: $abilities);
    $item = $procurement_with_singles_and_items_not_added_to_store->type === AllowedItemTypes::PRODUCT->value
        ? Product::query()->where('id', $procurement_with_singles_and_items_not_added_to_store->item_id)->first()
        : Variant::query()->where('id', $procurement_with_singles_and_items_not_added_to_store->item_id)->first();

    patch(uri: route('api:v1:executive:procurements:update_store', $procurement_with_singles_and_items_not_added_to_store->procurement->uuid))
        ->assertStatus(status: Http::ACCEPTED());

    $initial_store_quantity = $item->store;
    $new_store_quantity = $item->refresh()->store;
    expect(value: $new_store_quantity - $initial_store_quantity)
        ->toEqual(expected: $procurement_with_singles_and_items_not_added_to_store->number_of_single_pieces);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'procurement_with_singles_and_items_not_added_to_store');

it('can update the store quantity from a procurement of either crate box or pack form', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_crete_box_pack_and_items_not_added_to_store
) {
    actingAs(user: $executive, abilities: $abilities);
    $item = $procurement_with_crete_box_pack_and_items_not_added_to_store->type === AllowedItemTypes::PRODUCT->value
        ? Product::query()->where('id', $procurement_with_crete_box_pack_and_items_not_added_to_store->item_id)->first()
        : Variant::query()->where('id', $procurement_with_crete_box_pack_and_items_not_added_to_store->item_id)->first();

    patch(
        uri: route('api:v1:executive:procurements:update_store', $procurement_with_crete_box_pack_and_items_not_added_to_store->procurement->uuid)
    )->assertStatus(status: Http::ACCEPTED());

    $initial_store_quantity = $item->store;
    $new_store_quantity = $item->refresh()->store;
    expect(value: $new_store_quantity - $initial_store_quantity)
        ->toEqual(
            expected: $procurement_with_crete_box_pack_and_items_not_added_to_store->number_of_pieces_in_form * $procurement_with_crete_box_pack_and_items_not_added_to_store->form_quantity
        );
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'procurement_with_crete_box_pack_and_items_not_added_to_store');
