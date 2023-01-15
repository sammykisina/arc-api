<?php

declare(strict_types=1);

namespace Domains\Bartender\Models;

use Database\Factories\CounterItemFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounterItem extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'assigned',
        'sold', // among the assigned, what is sold?
        'price',
        'product_id',
        'form',
        'counter_id',
    ];

    public function counter(): BelongsTo {
        return $this->belongsTo(
            related:Counter::class,
            foreignKey: 'counter_id'
        );
    }

    protected static function newFactory(): CounterItemFactory {
        return new CounterItemFactory;
    }
}
