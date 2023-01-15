<?php

declare(strict_types=1);

namespace Domains\Shared\Actions;

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Domains\Shared\ValueObjects\EmployeeValueObject;
use Illuminate\Support\Facades\Hash;

class CreateEmployee {
    public static function handle(EmployeeValueObject $employeeValueObject): User {
        // find the passed in role
        $role = Role::query()->where('slug', $employeeValueObject->role)->first();

        return User::create([
            'work_id' => $employeeValueObject->work_id,
            'first_name' => $employeeValueObject->first_name,
            'last_name' => $employeeValueObject->last_name,
            'email' => $employeeValueObject->email,
            'password' => Hash::make(value: $employeeValueObject->password),
            'created_by' => auth()->user()->first_name.' '.auth()->user()->last_name.'id'.auth()->user()->work_id,
            'role_id' => $role->id,
        ]);
    }
}
