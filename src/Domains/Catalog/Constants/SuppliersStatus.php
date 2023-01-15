<?php

declare(strict_types=1);

namespace Domains\Catalog\Constants;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self suspended(),
 * @method static self active(),
 * @method static self underreview()
 */
class SuppliersStatus extends Enum {
}
