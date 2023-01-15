<?php

declare(strict_types=1);

use Domains\Shared\Models\Role;

dataset(
    name: 'role',
    dataset: [
        fn () => Role::factory()->create(),
    ]
);

dataset(
    name: 'admin_role',
    dataset: [
        fn () => Role::factory()->create([
            'name' => 'Super Admin',

        ]),
    ]
);
