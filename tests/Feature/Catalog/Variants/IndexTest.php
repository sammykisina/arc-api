<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot return all available variants if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:executive:variants:index'))->assertStatus(status: Http::CONFLICT());
})->with('user', 'variant');

it('can return all available variants with their product for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    get(uri: 'api/v1/executive/variants?include=product')->assertOk()
    ->assertJson(fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.product')
          ->hasAll('relationships.product.type', 'relationships.product.attributes')
          ->etc()
        ));
})->with('super_admin', 'variant');

it('can return all available variants with their product for admin', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    get(uri: 'api/v1/executive/variants?include=product')->assertOk()
    ->assertJson(fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.product')
          ->hasAll('relationships.product.type', 'relationships.product.attributes')
          ->etc()
        ));
})->with('admin', 'variant');
