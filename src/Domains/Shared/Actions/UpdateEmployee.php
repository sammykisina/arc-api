<?php

declare(strict_types=1);

namespace Domains\Shared\Actions;

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;

class UpdateEmployee {
    public static function handle(array $data, User $user): bool {
        // update the employee details
        $user->update(
            $data
        );

        if (array_key_exists('role', $data)) {
            $role = Role::query()->where('slug', $data['role'])->first();
            $user->update([
                'role_id' => $role->id,
            ]);
        }

        return true;
    }
}
