<?php

declare(strict_types=1);

namespace Domains\Shared\Actions;

use Domains\Shared\Models\Role;
use Domains\Shared\ValueObjects\RoleValueObject;

class CreateRole {
    public static function handle(RoleValueObject $roleValueObject): Role {
        return Role::create($roleValueObject->toArray());
    }
}
