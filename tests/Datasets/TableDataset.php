<?php

declare(strict_types=1);

use Domains\Fulfillment\Models\Table;

dataset(
    name: 'table',
    dataset: [
        fn () => Table::factory()->create(),
    ]
);

dataset(
    name: 'extendable_table',
    dataset: [
        fn () => Table::factory()->create([
            'extendable' => true,
            'number_of_extending_seats' => 5,
        ]),
    ]
);

dataset(
    name: 'non_extendable_table',
    dataset: [
        fn () => Table::factory()->create([
            'extendable' => false,
            'number_of_extending_seats' => null,
        ]),
    ]
);
