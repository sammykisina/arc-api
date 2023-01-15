<?php

declare(strict_types=1);

use Domains\Shared\Models\User;

dataset(
    name: 'super_admin',
    dataset: [
        fn () => User::factory()->create([
            'role_id' => 1,
        ]),
    ]
);

dataset(
    name: 'admin',
    dataset: [
        fn () => User::factory()->create([
            'role_id' => 2,
        ]),
    ]
);

dataset(
    name: 'executive',
    dataset: [
        fn () => User::factory()->executive()->create(),
    ]
);

dataset(
    name: 'bartender',
    dataset: [
        fn () => User::factory()->create([
            'role_id' => 3,
        ]),
    ]
);

dataset(
    name: 'waiter',
    dataset: [
        fn () => User::factory()->create([
            'role_id' => 4,
        ]),
    ]
);

dataset(
    name: 'user',
    dataset: [
        fn () => User::factory()->create(
            [
                'work_id' => 123456,
                'password' => bcrypt(value: 'my password'),
            ]
        ),
    ]
);

dataset(
    name: 'employee',
    dataset: [
        fn () => User::factory()->create(
            [
                'work_id' => '9632589',
                'password' => bcrypt(value: 9632589),
                'role_id' => 4
            ]
        ),
    ]
);
