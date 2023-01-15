<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Status;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self pending(),
 * @method static self completed(),
 * @method static self cancelled()
 */
final class OrderStatus extends Enum {
}
