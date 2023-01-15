<?php

declare(strict_types=1);

use Domains\Catalog\Models\Supplier;
use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a supplier if the current user is not super admin or admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:executive:suppliers:store'))->assertStatus(status: Http::CONFLICT());
})->with('user');

it('can validate the supplier info', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(uri: route('api:v1:executive:suppliers:store'))->assertSessionHasErrors(keys: ['name', 'location', 'phone_number', 'email']);
})->with('admin');

it('cannot create two or more suppliers with the same name', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:suppliers:store'),
        data: [
            'name' => $supplier->name,
            'location' => 'some location',
            'phone_number' => '0717550225',
            'email' => 'some_email@gmail.com',
        ]
    )->assertSessionHasErrors(keys: ['name']);
})->with('admin', 'supplier');

it('cannot create two or suppliers with the same email', function (User $admin, Supplier $supplier) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:suppliers:store'),
        data: [
            'name' => 'supplier name',
            'location' => 'some location',
            'phone_number' => '0717550225',
            'email' => $supplier->email,
        ]
    )->assertSessionHasErrors(keys: ['email']);
})->with('admin', 'supplier');

it('can create a supplier for super admin', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(
        uri: route('api:v1:executive:suppliers:store'),
        data: [
            'name' => 'supplier name',
            'location' => 'some location',
            'phone_number' => '0717550225',
            'email' => 'some_email@gmail.com',
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
        ->has(key: 3)
        ->hasAll('error', 'message', 'supplier')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Supplier Created Successfully.')
        ->has(
            'supplier',
            fn ($json) => $json
          ->hasAll('id', 'type', 'attributes')
          ->where(key: 'type', expected:'supplier')
          ->where(key: 'attributes.name', expected: 'supplier name')
          ->where(key: 'attributes.contact_info.email', expected: 'some_email@gmail.com')
          ->where(key: 'attributes.status', expected: 'underreview')
          ->etc()
        ));

    $this->assertDatabaseHas('suppliers', [
        'name' => 'supplier name',
        'email' => 'some_email@gmail.com',
    ]);
})->with('super_admin');

it('can create a supplier for admin', function (User $admin) {
    actingAs(user: $admin, abilities: ['admin']);

    post(
        uri: route('api:v1:executive:suppliers:store'),
        data: [
            'name' => 'supplier name',
            'location' => 'some location',
            'phone_number' => '0717550225',
            'email' => 'some_email@gmail.com',
        ]
    )->assertStatus(status: Http::CREATED())
      ->assertJson(fn (AssertableJson $json) => $json
        ->has(key: 3)
        ->hasAll('error', 'message', 'supplier')
        ->where(key: 'error', expected: 0)
        ->where(key: 'message', expected: 'Supplier Created Successfully.')
        ->has(
            'supplier',
            fn ($json) => $json
          ->hasAll('id', 'type', 'attributes')
          ->where(key: 'type', expected:'supplier')
          ->where(key: 'attributes.name', expected: 'supplier name')
          ->where(key: 'attributes.contact_info.email', expected: 'some_email@gmail.com')
          ->where(key: 'attributes.status', expected: 'underreview')
          ->etc()
        ));

    $this->assertDatabaseHas('suppliers', [
        'name' => 'supplier name',
        'email' => 'some_email@gmail.com',
    ]);
})->with('admin');
