<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Database\Factories\OrderlineFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orderline extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'retail',
        'cost',
        'quantity',
        'order_id',
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(
            related: Order::class,
            foreignKey: 'order_id'
        );
    }

    protected static function newFactory(): OrderlineFactory {
        return new OrderlineFactory;
    }
}
