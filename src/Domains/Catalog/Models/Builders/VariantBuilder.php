<?php

declare(strict_types=1);

namespace Domains\Catalog\Models\Builders;

use Domains\Shared\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Builder;

class VariantBuilder extends Builder {
    use HasActiveScope; // active and inactive
}
