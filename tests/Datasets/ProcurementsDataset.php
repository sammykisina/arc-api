<?php

declare(strict_types=1);

use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\Supplier;

dataset(
    name: '4_procurements',
    dataset:[
        fn () => Procurement::factory(count: 4)->create(),
    ]
);

dataset(
    name: 'procurement',
    dataset:[
        fn () => Procurement::factory()->create(),
    ]
);

dataset(
    name: 'pending_procurement',
    dataset:[
        fn () => Procurement::factory()->pending()->create(),
    ]
);

dataset(
    name: 'pending_procurement_for_a_specific_supplier',
    dataset:[
        fn () => Procurement::factory()->pending()->create([
            'supplier_id' => Supplier::factory()->active()->create()
        ]),
    ]
);

dataset(
    name: 'delivered_procurement',
    dataset:[
        fn () => Procurement::factory()->delivered()->create(),
    ]
);
