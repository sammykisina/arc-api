<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\SupplierFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'number_of_closed_deals',
        'rates',
        'location',
        'phone_number',
        'email',
        'status',
    ];

    public function variants(): BelongsToMany {
        return $this->belongsToMany(
            related: Variant::class,
            table: 'supplier_variant',
        );
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany(
            related: Product::class,
            table: 'supplier_product'
        );
    }

    public function procurements(): HasMany {
        return $this->hasMany(
            related: Procurement::class,
            foreignKey:'supplier_id'
        );
    }

    protected static function newFactory(): SupplierFactory {
        return new SupplierFactory;
    }
}
