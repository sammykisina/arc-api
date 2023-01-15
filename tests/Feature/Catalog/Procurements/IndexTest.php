<?php

declare(strict_types=1);

use Domains\Catalog\Models\ProcurementItem;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot only return available procurements if thr current user super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:executive:procurements:index'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can return the available procurements with singles for super admin', function (User $super_admin, ProcurementItem $procurement_item) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: 'api/v1/executive/procurements?include=item,supplier')
    ->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected: 'procurement')
            ->where(key: 'id', expected: $procurement_item->procurement_id)
            ->hasAll('relationships.item.type', 'relationships.item.id')
            ->where(key: 'relationships.item.type', expected: 'procurement_item')
            ->where(key: 'relationships.item.id', expected: $procurement_item->id)
            ->etc()
          )
      );
})->with('super_admin', 'procurement_with_singles');

it('can return the available procurements with either crate box or pack for super admin', function (User $super_admin, ProcurementItem $procurement_item) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: 'api/v1/executive/procurements?include=item,supplier')
    ->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected: 'procurement')
            ->where(key: 'id', expected: $procurement_item->procurement_id)
            ->hasAll('relationships.item.type', 'relationships.item.id')
            ->where(key: 'relationships.item.type', expected: 'procurement_item')
            ->where(key: 'relationships.item.id', expected: $procurement_item->id)
            ->etc()
          )
      );
})->with('super_admin', 'procurement_with_crate_box_pack');

it('can return the available procurements with singles for admin', function (User $admin, ProcurementItem $procurement_item) {
    actingAs(user: $admin, abilities:['admin']);

    get(uri: 'api/v1/executive/procurements?include=item,supplier')
    ->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected: 'procurement')
            ->where(key: 'id', expected: $procurement_item->procurement_id)
            ->hasAll('relationships.item.type', 'relationships.item.id')
            ->where(key: 'relationships.item.type', expected: 'procurement_item')
            ->where(key: 'relationships.item.id', expected: $procurement_item->id)
            ->etc()
          )
      );
})->with('admin', 'procurement_with_singles');

it('can return the available procurements with either crate box or pack for admin', function (User $admin, ProcurementItem $procurement_item) {
    actingAs(user: $admin, abilities:['admin']);

    get(uri: 'api/v1/executive/procurements?include=item,supplier')
    ->assertOk()
      ->assertJson(
          fn (AssertableJson $json) => $json
          ->first(
              fn ($json) => $json
            ->hasAll('type', 'attributes', 'relationships.item', 'relationships.supplier')
            ->where(key: 'type', expected: 'procurement')
            ->where(key: 'id', expected: $procurement_item->procurement_id)
            ->hasAll('relationships.item.type', 'relationships.item.id')
            ->where(key: 'relationships.item.type', expected: 'procurement_item')
            ->where(key: 'relationships.item.id', expected: $procurement_item->id)
            ->etc()
          )
      );
})->with('admin', 'procurement_with_crate_box_pack');
