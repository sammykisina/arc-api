<?php

declare(strict_types=1);

namespace Domains\Catalog\Models\Builders;

use Domains\Shared\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Builder;

class ProductBuilder extends Builder {
    use HasActiveScope;

    public function dependent(): self {
        return $this->where(column:'form', operator:'dependent');
    }

    public function independent(): self {
        return $this->where(column:'form', operator:'independent');
    }
}
