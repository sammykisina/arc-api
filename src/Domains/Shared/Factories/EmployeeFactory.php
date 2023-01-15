<?php

declare(strict_types=1);

namespace Domains\Shared\Factories;

use Domains\Shared\ValueObjects\EmployeeValueObject;

class EmployeeFactory {
    public static function make(array $attributes): EmployeeValueObject {
        return new EmployeeValueObject(
            work_id: $attributes['work_id'],
            first_name: $attributes['first_name'],
            last_name: $attributes['last_name'],
            email: $attributes['email'],
            password: $attributes['password'],
            role: $attributes['role']
        );
    }
}
