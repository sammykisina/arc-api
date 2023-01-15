<?php

declare(strict_types=1);

use Domains\Catalog\Models\Variant;

dataset(
    name: 'variant',
    dataset: [
        fn () => Variant::factory()->create(),
    ]
);

dataset(
    name: 'variant_with_no_items_in_store',
    dataset: [
        fn () => Variant::factory()->create([
            'store' => 0,
        ]),
    ]
);

dataset(
    name: 'four_variants',
    dataset: [
        fn () => Variant::factory(count: 4)->create(),
    ]
);
