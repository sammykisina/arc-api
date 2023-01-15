<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('cannot return employees if the current user is not the super admin', function (User $user) {
    actingAs(user: $user);

    get(uri: route('api:v1:superadmin:employees:index'))->assertStatus(status:Http::CONFLICT());
})->with('user');

it('can return all available employees', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    get(uri: route('api:v1:superadmin:employees:index'))
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes')
          ->etc()
        )
      ->etc()
      );
})->with('super_admin');

it('can return all available employees with their respective role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);
    get('api/v1/superadmin/employees?include=role')
      ->assertStatus(status: Http::OK())
      ->assertJson(
          fn (AssertableJson $json) => $json
        ->first(
            fn ($json) => $json
          ->hasAll('type', 'attributes', 'relationships.role')
          ->etc()
        )
      ->etc()
      );
})->with('super_admin');
