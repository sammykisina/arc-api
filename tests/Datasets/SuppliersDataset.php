<?php

declare(strict_types=1);

use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Supplier;
use Domains\Catalog\Models\Variant;

dataset(
    name: 'supplier',
    dataset: [
        fn () => Supplier::factory()->create(),
    ]
);

dataset(
    name: 'supplier_with_variant_and_product',
    dataset:[
        fn () => Supplier::factory()
        ->has(Variant::factory())
        ->has(Product::factory()->independent())
        ->create(),
    ]
);

dataset(
    name: 'active_supplier',
    dataset: [
        fn () => Supplier::factory()->active()->create(),
    ]
);

dataset(
    name: 'inactive_supplier',
    dataset: [
        fn () => Supplier::factory()->inactive()->create(),
    ]
);
