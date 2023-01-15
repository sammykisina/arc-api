<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\get;

it('does not return the available roles if the user is not a super admin', function (User $user) {
    actingAs(user:$user);
    get('/api/v1/superadmin/roles?include=users')->assertStatus(status: Http::CONFLICT());
})->with('user');

it('return the available roles with their owners if the user is a super admin', function (User $super_admin) {
    actingAs(user:$super_admin, abilities:['super-admin']);

    get('/api/v1/superadmin/roles?include=users')
        ->assertStatus(status: Http::OK())
            ->assertJson(
                fn (AssertableJson $json) => $json
            ->first(
                fn ($json) => $json
                ->hasAll('type', 'attributes', 'relationships.users')
                ->etc()
            )
            );
})->with('super_admin');
