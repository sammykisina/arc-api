<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions;

use Domains\Catalog\Models\Product;
use Domains\Catalog\ValueObjects\ProductValueObject;

class CreateProduct {
    public static function handle(ProductValueObject $productValueObject): Product {
        return Product::create([
            'name' => $productValueObject->name,
            'cost' => $productValueObject->cost,
            'retail' => $productValueObject->retail,
            'stock' => $productValueObject->stock,
            'store' => $productValueObject->stock,
            'measure' => $productValueObject->measure,
            'category_id' => $productValueObject->category_id,
            'form' => $productValueObject->form,
            'vat' => $productValueObject->vat,
        ]);
    }
}
