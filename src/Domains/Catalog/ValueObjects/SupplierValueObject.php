<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class SupplierValueObject {
    public function __construct(
        public string $name,
        public string $location,
        public string $phone_number,
        public string $email,
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
        ];
    }
}
