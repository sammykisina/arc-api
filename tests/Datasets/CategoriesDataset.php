<?php

declare(strict_types=1);

use Domains\Catalog\Models\Category;

dataset(
    name: 'category',
    dataset: [
        fn () => Category::factory()->create(),
    ]
);
