<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Database\Factories\PaymentFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'number',
        'method',
        'owner',
        'code',
        'amount',
        'date',
        'time',
    ];

    public function payments(): BelongsToMany {
        return $this->belongsToMany(
            related: Order::class,
            table: 'order_payments'
        );
    }

    protected static function newFactory(): PaymentFactory {
        return new PaymentFactory;
    }
}
