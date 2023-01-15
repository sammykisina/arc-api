<?php

declare(strict_types=1);

namespace Domains\Bartender\Models;

use Database\Factories\CounterFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'shift_id',
    ];

    public function shift(): BelongsTo {
        return $this->belongsTo(
            related: Shift::class,
            foreignKey: 'shift_id'
        );
    }

    public function items(): HasMany {
        return $this->hasMany(
            related: CounterItem::class,
            foreignKey: 'counter_id'
        );
    }

    protected static function newFactory(): CounterFactory {
        return new CounterFactory;
    }
}
