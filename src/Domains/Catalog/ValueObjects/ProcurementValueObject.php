<?php

declare(strict_types=1);

namespace Domains\Catalog\ValueObjects;

class ProcurementValueObject {
    public function __construct(
        public int $supplier_id,
        public string $type,
        public int $item_id,
        public string $form,
        public ?int $form_quantity,
        public ?int $number_of_single_pieces,
        public int $measure,
    ) {
    }

    public function toArray(): array {
        return [
            'supplier_id' => $this->supplier_id,
            'type' => $this->type,
            'item_id' => $this->item_id,
            'form' => $this->form,
            'form_quantity' => $this->form_quantity,
            'number_of_single_pieces' => $this->number_of_single_pieces,
            'measure' => $this->measure,
        ];
    }
}
