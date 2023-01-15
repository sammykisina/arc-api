<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\post;

it('cannot create a new employee if the current user is not the super admin', function (User $user) {
    actingAs(user: $user);

    post(uri: route('api:v1:superadmin:employees:store'))->assertStatus(status:Http::CONFLICT());
})->with('user');

it('validate all the new employee details', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(uri: route('api:v1:superadmin:employees:store'))
      ->assertSessionHasErrors(keys:['work_id', 'first_name', 'last_name', 'email', 'password', 'role']);
})->with('super_admin');

it('create employee and return the new created employee', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);
    post(uri: route('api:v1:superadmin:employees:store'), data: [
        'work_id' => '34996981',
        'first_name' => 'sammy',
        'last_name' => 'mutua',
        'email' => 'sammy@gmail.com',
        'password' => '34996981',
        'role' => 'admin',
    ])
    ->assertStatus(status: Http::CREATED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(key: 3)
      ->hasAll('error', 'message', 'employee')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Employee Created Successfully.')
      ->has(
          'employee',
          fn ($json) => $json
        ->hasAll('id', 'type', 'attributes')
        ->where(key: 'type', expected:'employee')
        ->where(key: 'attributes.work_id', expected: 34996981)
        ->where(key: 'attributes.email', expected: 'sammy@gmail.com')
        ->etc()
      )
    );

    $this->assertDatabaseHas('users', [
        'work_id' => '34996981',
        'email' => 'sammy@gmail.com',
    ]);
})->with('super_admin');

it('ensures that all employees work_ids and emails are unique', function (User $super_admin, User $employee) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(uri: route('api:v1:superadmin:employees:store'), data: [
        'work_id' => $employee->work_id,
        'first_name' => 'sammy',
        'last_name' => 'mutua',
        'email' => $employee->email,
        'password' => '34996981',
        'role' => 'admin',
    ])->assertSessionHasErrors(keys:['work_id']);
})->with('super_admin', 'employee');

it('cannot create an employee with the super-admin role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(uri: route('api:v1:superadmin:employees:store'), data: [
        'work_id' => '34996981',
        'first_name' => 'sammy',
        'last_name' => 'mutua',
        'email' => 'sammy@gmail.com',
        'password' => '34996981',
        'role' => 'super-admin',
    ])->assertStatus(status: Http::NOT_IMPLEMENTED());
})->with('super_admin');

it('it cannot create two admins', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(uri: route('api:v1:superadmin:employees:store'), data: [
        'work_id' => '34996981',
        'first_name' => 'sammy',
        'last_name' => 'mutua',
        'email' => 'sammy@gmail.com',
        'password' => '34996981',
        'role' => 'admin',
    ])->assertStatus(status: Http::NOT_IMPLEMENTED());
})->with('super_admin', 'admin');

it('cannot assign a role to an employee that does not exists', function (User $super_admin) {
    actingAs(user: $super_admin, abilities: ['super-admin']);

    post(uri: route('api:v1:superadmin:employees:store'), data: [
        'work_id' => '34996981',
        'first_name' => 'sammy',
        'last_name' => 'mutua',
        'email' => 'sammy@gmail.com',
        'password' => '34996981',
        'role' => 'some-unknown-role',
    ])->assertSessionHasErrors(keys:['role']);
})->with('super_admin');
