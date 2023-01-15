<?php

declare(strict_types=1);

namespace Domains\Fulfillment\ValueObjects;

class TableValueObject {
    public function __construct(
        public string $name,
        public int $number_of_seats,
        public bool $extendable,
        public ?int $number_of_extending_seats
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'number_of_seats' => $this->number_of_seats,
            'extendable' => $this->extendable,
            'number_of_extending_seats' => $this->number_of_extending_seats,
        ];
    }
}
