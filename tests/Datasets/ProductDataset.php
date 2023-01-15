<?php

declare(strict_types=1);

use Domains\Catalog\Models\Product;

dataset(
    name: 'product',
    dataset: [
        fn () => Product::factory()->create(),
    ]
);

dataset(
    name: 'independent_product',
    dataset: [
        fn () => Product::factory()->independent()->create(),
    ]
);

dataset(
    name: 'independent_product_with_no_items_in_store',
    dataset: [
        fn () => Product::factory()->independent()->create([
            'store' => 0,
        ]),
    ]
);

dataset(
    name: 'dependent_product',
    dataset: [
        fn () => Product::factory()->dependent()->create(),
    ]
);

dataset(
    name: 'four_independent_products',
    dataset: [
        fn () => Product::factory(count: 4)->independent()->create(),
    ]
);
