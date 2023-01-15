<?php

declare(strict_types=1);

use Domains\Shared\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\delete;

it('cannot delete an employee if the current user is not a super admin', function (User $user, User $employee) {
    actingAs(user:$user);

    delete(route('api:v1:superadmin:employees:delete', $employee->uuid))->assertStatus(status: Http::CONFLICT());
})->with('user', 'employee');

it('cannot delete the super admin', function (User $super_admin) {
    actingAs(user:$super_admin, abilities:['super-admin']);

    delete(route('api:v1:superadmin:employees:delete', $super_admin->uuid))->assertStatus(status: Http::NOT_ACCEPTABLE());
})->with('super_admin');

it('can delete an employee', function (User $super_admin, User $employee) {
    actingAs(user:$super_admin, abilities:['super-admin']);

    delete(route('api:v1:superadmin:employees:delete', $employee->uuid))->assertStatus(status: Http::ACCEPTED())
    ->assertJson(
        fn (AssertableJson $json) => $json
      ->has(2)
      ->hasAll('error', 'message')
      ->where(key: 'error', expected: 0)
      ->where(key: 'message', expected: 'Employee Deleted Successfully.')
    );

    $this->assertDatabaseMissing('users', [
        'work_id' => $employee->work_id,
        'email' => $employee->email,
    ]);
})->with('super_admin', 'employee');
