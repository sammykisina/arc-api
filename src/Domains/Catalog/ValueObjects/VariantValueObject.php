<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class VariantValueObject {
    public function __construct(
        public string $name,
        public int $cost,
        public int $retail,
        public int $stock,
        public int $measure,
        public int $product_id,
        public bool $vat
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'cost' => $this->cost,
            'retail' => $this->retail,
            'stock' => $this->stock,
            'measure' => $this->measure,
            'product_id' => $this->product_id,
            'vat' => $this->vat,
        ];
    }
}
