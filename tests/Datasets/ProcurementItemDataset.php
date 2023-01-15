<?php

declare(strict_types=1);

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;

dataset(
    name: 'procurement_with_singles',
    dataset:[
        fn () => ProcurementItem::factory()->singles()->create(),
    ]
);

dataset(
    name: 'procurement_with_singles_product',
    dataset:[
        fn () => ProcurementItem::factory()->singles()->create([
            'item_id' => Product::factory()->independent()->create([
                'store' => 0,
            ]),
            'type' => AllowedItemTypes::PRODUCT->value,
        ]),
    ]
);

dataset(
    name: 'procurement_with_singles_variant',
    dataset:[
        fn () => ProcurementItem::factory()->singles()->create([
            'item_id' => Variant::factory()->create([
                'store' => 0,
            ]),
            'type' => AllowedItemTypes::VARIANT->value,
        ]),
    ]
);

dataset(
    name: 'procurement_with_crate_box_pack',
    dataset:[
        fn () => ProcurementItem::factory()->crate_box_pack()->create(),
    ]
);

dataset(
    name: 'procurement_with_items_added_to_store',
    dataset:[
        fn () => ProcurementItem::factory()->added_to_store()->create([
            'procurement_id' => Procurement::factory()->delivered(),
        ]),
    ]
);

dataset(
    name: 'procurement_with_items_not_added_to_store',
    dataset:[
        fn () => ProcurementItem::factory()->not_added_to_store()->create([
            'procurement_id' => Procurement::factory()->delivered(),
        ]),
    ]
);

dataset(
    name: 'procurement_with_singles_and_items_not_added_to_store',
    dataset:[
        fn () => ProcurementItem::factory()->singles()->not_added_to_store()->create([
            'procurement_id' => Procurement::factory()->delivered(),
        ]),
    ]
);

dataset(
    name: 'procurement_with_crete_box_pack_and_items_not_added_to_store',
    dataset:[
        fn () => ProcurementItem::factory()->crate_box_pack()->not_added_to_store()->create([
            'procurement_id' => Procurement::factory()->delivered(),
        ]),
    ]
);

dataset(
    name: 'pending_procurement_with_items_not_added_to_store',
    dataset:[
        fn () => ProcurementItem::factory()->not_added_to_store()->create([
            'procurement_id' => Procurement::factory()->pending(),
        ]),
    ]
);
