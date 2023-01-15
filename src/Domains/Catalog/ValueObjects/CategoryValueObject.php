<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class CategoryValueObject {
    public function __construct(
        public string $name,
        public string $description
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
