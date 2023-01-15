<?php

declare(strict_types=1);

namespace Domains\Catalog\Constants;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self delivered(),
 * @method static self pending(),
 * @method static self cancelled(),
 */
class ProcurementStatus extends Enum {
}
