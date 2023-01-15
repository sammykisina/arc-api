<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)
->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function actingAs(Authenticatable $user, array $abilities = []) {
    Sanctum::actingAs(user: $user, abilities:$abilities);
    return test();
}

// function assertNotAllowed()
// {
//     return test()->assertStatus(status: Http::CONFLICT());
// }

// expect()->extend(name: "toBeForbiddenFor", extend: function (string $url, string $method = "get"){
//     dd($this->value);
// });
