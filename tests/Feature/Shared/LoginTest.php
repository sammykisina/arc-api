<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('validates the login request details', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:auth:login'))->assertSessionHasErrors(['work_id', 'password']);
})->with('user');

it('cannot login a user who does not exits', function () {
    post(uri:route('api:v1:auth:login'), data: [
        'work_id' => '1234',
        'password' => 'password',
    ])->assertStatus(Http::NOT_FOUND());
});

it('cannot login a user with wrong password', function (User $user) {
    actingAs(user: $user);

    post(
        uri: route('api:v1:auth:login'),
        data: [
            'work_id' => $user->work_id,
            'password' => 'some wrong password',
        ]
    )->assertStatus(Http::NOT_FOUND());
})->with('user');

it('can login a user with the right credentials', function (User $user) {
    actingAs(user: $user);

    post(
        uri: route('api:v1:auth:login'),
        data: [
            'work_id' => $user->work_id,
            'password' => 'my password',
        ]
    )
        ->assertStatus(status: Http::OK())
        ->assertJson(
            fn (AssertableJson $json) => $json
            ->where(key: 'error', expected: 0)
            ->has(key:'token')
            ->etc()
        );
})->with('user');
