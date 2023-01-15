<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model {
    use HasUuid;

    protected $fillable = [
        'uuid',
        'order_id',
        'payment_id',
    ];
}
