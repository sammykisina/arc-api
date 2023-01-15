<?php

declare(strict_types=1);

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\patch;

it('cannot update the employee details if the current user is not the super admin', function (User $user, User $employee) {
    actingAs(user:$user);

    patch(route('api:v1:superadmin:employees:update', $employee->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'employee');

it('cannot update an employee who does exists', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    patch(route('api:v1:superadmin:employees:update', Str::uuid()->toString()))->assertStatus(status: Http::NOT_FOUND());
})->with('super_admin');

it('cannot update the super admins role', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    patch(uri:route('api:v1:superadmin:employees:update', $super_admin->uuid), data:[
        'role' => 'admin',
    ])->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('super_admin');

it('cannot update the super admins work_id', function (User $super_admin) {
    actingAs(user: $super_admin, abilities:['super-admin']);

    patch(uri:route('api:v1:superadmin:employees:update', $super_admin->uuid), data:[
        'work_id' => '2356892',
    ])->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('super_admin');

it('it can update employee details', function (
    User $super_admin,
    User $employee,
    Role $role
): void {
    actingAs(user: $super_admin, abilities:['super-admin'])
    ->patch(
        uri:route('api:v1:superadmin:employees:update', $employee->uuid),
        data:[
            'work_id' => '85624578',
            'role' => $role->slug,
        ]
    )->assertStatus(status: Http::ACCEPTED())
        ->assertJson(
            fn (AssertableJson $json) => $json
            ->has(2)
            ->hasAll('error', 'message')
            ->where(key: 'error', expected: 0)
            ->where(key: 'message', expected: 'Employee Details Updated Successfully.')
        );

    $this->assertDatabaseHas('users', [
        'work_id' => '85624578',
        'role_id' => $role->id,
    ]);
})->with('super_admin', 'employee', 'role');
