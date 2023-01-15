<?php

declare(strict_types=1);

use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete a variant if the current user is not super admin or admin', function (User $user, Variant $variant) {
    actingAs(user: $user);

    delete(uri: route('api:v1:executive:variants:delete', $variant->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'variant');

it('cannot delete a variant that does not exits', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    delete(uri: route('api:v1:executive:variants:delete', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('admin');

it('cannot delete a variant with items in the store', function (
    array $abilities,
    User $executive,
    Variant $variant
) {
    actingAs(user: $executive, abilities: $abilities);

    delete(uri: route('api:v1:executive:variants:delete', $variant->uuid))->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'variant');

it('cannot delete a variant linked to unassigned procurement', function (
    array $abilities,
    User $executive,
    ProcurementItem $procurement_with_singles_variant
) {
    actingAs(user: $executive, abilities: $abilities);
    $variant = Variant::query()->where('id', $procurement_with_singles_variant->item_id)->first();

    delete(uri: route('api:v1:executive:variants:delete', $variant->uuid))
        ->assertStatus(status: Http::UNPROCESSABLE_ENTITY());
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'procurement_with_singles_variant');

it('can delete a variant', function (
    array $abilities,
    User $executive,
    Variant $variant_with_no_items_in_store
) {
    actingAs(user: $executive, abilities: $abilities);

    delete(uri: route('api:v1:executive:variants:delete', $variant_with_no_items_in_store->uuid))
        ->assertStatus(status: Http::ACCEPTED())
            ->assertJson(
                fn (AssertableJson $json) => $json
            ->has(2)
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Variant Deleted Successfully.')
            );

    $this->assertDatabaseCount('variants', 0)->assertDatabaseMissing('variants', [
        'id' => $variant_with_no_items_in_store->id,
        'uuid' => $variant_with_no_items_in_store->uuid,
    ]);
})->with([
    ['abilities' => ['super-admin']],
    ['abilities' => ['admin']],
], 'executive', 'variant_with_no_items_in_store');
