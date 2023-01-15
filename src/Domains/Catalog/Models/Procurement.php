<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\ProcurementFactory;
use Domains\Catalog\Models\Concerns\HasNumber;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Procurement extends Model {
    use HasFactory;
    use HasUuid;
    use HasNumber;

    protected $fillable = [
        'uuid',
        'number',
        'total_cost',
        'status',
        'procurement_date',
        'due_date',
        'delivered_date',
        'cancelled_date',
        'supplier_id',
    ];

    protected $casts = [
        'procurement_date' => 'datetime',
        'due_date' => 'datetime',
        'delivered_date' => 'datetime',
        'cancelled_date' => 'datetime',
    ];

    public function item(): HasOne {
        return $this->hasOne(
            related: ProcurementItem::class,
            foreignKey: 'procurement_id'
        );
    }

    public function supplier(): BelongsTo {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id'
        );
    }

    protected static function newFactory(): ProcurementFactory {
        return new ProcurementFactory;
    }
}
