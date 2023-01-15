<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class ProductValueObject {
    public function __construct(
        public string $name,
        public ?int $cost,
        public ?int $retail,
        public ?int $stock,
        public ?int $measure,
        public int $category_id,
        public string $form,
        public ?bool $vat
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'cost' => $this->cost,
            'retail' => $this->retail,
            'stock' => $this->stock,
            'measure' => $this->measure,
            'category_id' => $this->category_id,
            'form' => $this->form,
            'vat' => $this->vat,
        ];
    }
}
