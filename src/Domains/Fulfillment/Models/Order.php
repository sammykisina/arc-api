<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Carbon\Carbon;
use Database\Factories\OrderFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'number',
        'status',
        'sub_total',
        'discount',
        'total',
        'table_id',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function items(): HasMany {
        return $this->hasMany(
            related: Orderline::class,
            foreignKey: 'order_id',
        );
    }

    public function table(): BelongsTo {
        return $this->belongsTo(
            related: Table::class,
            foreignKey: 'table_id'
        );
    }

    public function payments(): BelongsToMany {
        return $this->belongsToMany(
            related: Payment::class,
            table: 'order_payments'
        );
    }

    public static function generateOrderNumber(int $active_shift_id): string {
        $date_time = Carbon::now();

        $date = $date_time->toDateString();
        $time = $date_time->toTimeString();

        return $date.'/'.$time.'/'.$active_shift_id;
    }

    protected static function newFactory(): OrderFactory {
        return new OrderFactory;
    }
}
