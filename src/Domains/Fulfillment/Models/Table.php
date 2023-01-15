<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Models;

use Database\Factories\TableFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'number_of_seats',
        'extendable',
        'number_of_extending_seats',
    ];

    protected $casts = [
        'extendable' => 'boolean',
    ];

    public function orders(): HasMany {
        return $this->hasMany(
            related: Order::class,
            foreignKey: 'table_id'
        );
    }

    protected static function newFactory(): TableFactory {
        return new TableFactory;
    }
}
