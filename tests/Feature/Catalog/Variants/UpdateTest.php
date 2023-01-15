<?php

declare(strict_types=1);

use Domains\Catalog\Models\Variant;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update a variant if the current user is not the super admin or admin', function (User $user, Variant $variant) {
    actingAs(user: $user);

    patch(uri: route('api:v1:executive:variants:update', $variant->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'variant');

it('cannot update a variant that does not exits', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(uri: route('api:v1:executive:variants:update', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('admin');

it('cannot update a variant to a name and measure of an existing variant', function (User $admin, Variant $variant) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:variants:update', $variant->uuid),
        data: [
            'name' => $variant->name,
            'measure' => $variant->measure,
        ]
    )->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('admin', 'variant');

it('cannot update a variant to an measure equal to another variant variant measure with the same name', function (User $admin, Variant $variant) {
    actingAs(user: $admin, abilities: ['admin']);
    $another_variant = Variant::factory()->create([
        'name' => $variant->name,
    ]);

    patch(
        uri: route('api:v1:executive:variants:update', $variant->uuid),
        data: [
            'measure' => $another_variant->measure,
        ]
    )->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('admin', 'variant');

it('can update a variant for super admin', function (User $super_admin, Variant $variant) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    patch(
        uri: route('api:v1:executive:variants:update', $variant->uuid),
        data: [
            'name' => 'updated new variant name',
            'measure' => 250,
            'cost' => 50,
            'retail' => 60,
            'stock' => 25,
            'store' => 25,
        ]
    )->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
              ->has(2)
              ->hasAll('error', 'message')
              ->where(key: 'error', expected: 0)
              ->where(key: 'message', expected: 'Variant Updated Successfully.')
        );

    $this->assertDatabaseHas('variants', [
        'name' => 'updated new variant name',
        'cost' => 50,
        'retail' => 60,
        'stock' => 25,
        'store' => 25,
    ]);
})->with('super_admin', 'variant');

it('can update a variant for admin', function (User $admin, Variant $variant) {
    actingAs(user: $admin, abilities: ['admin']);

    patch(
        uri: route('api:v1:executive:variants:update', $variant->uuid),
        data: [
            'name' => 'updated new variant name',
            'measure' => 250,
            'cost' => 50,
            'retail' => 60,
            'stock' => 25,
            'store' => 25,
        ]
    )->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
              ->has(2)
              ->hasAll('error', 'message')
              ->where(key: 'error', expected: 0)
              ->where(key: 'message', expected: 'Variant Updated Successfully.')
        );

    $this->assertDatabaseHas('variants', [
        'name' => 'updated new variant name',
        'cost' => 50,
        'retail' => 60,
        'stock' => 25,
        'store' => 25,
    ]);
})->with('admin', 'variant');
