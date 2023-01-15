<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class TokenValueObject {
    public function __construct(
        public string $name,
        public int $number_of_single_pieces,
        public int $measure,
        public string $owner,
        public ?int $item_id,
        public ?string $item_type,
        public bool $approved
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'number_of_single_pieces' => $this->number_of_single_pieces,
            'measure' => $this->measure,
            'owner' => $this->owner,
            'item_id' => $this->item_id,
            'item_type' => $this->item_type,
            'approved' => $this->approved
        ];
    }
}
