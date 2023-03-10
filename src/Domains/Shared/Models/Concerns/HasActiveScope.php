<?php

declare(strict_types=1);

namespace Domains\Shared\Models\Concerns;

trait HasActiveScope {
    public function active(): self {
        return $this->where('active', true);
    }

    public function inactive(): self {
        return $this->where('active', true);
    }
}
