<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\VariantFactory;
use Domains\Catalog\Models\Builders\VariantBuilder;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Variant extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'cost',
        'retail',
        'stock',
        'store',
        'sold',
        'measure',
        'active',
        'vat',
        'product_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'vat' => 'boolean',
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(
            related: Product::class,
            foreignKey: 'product_id'
        );
    }

    public function suppliers(): BelongsToMany {
        return $this->belongsToMany(
            related:SupplierVariant::class,
            table:'supplier_variants',
        );
    }

    public function newEloquentBuilder($query): Builder {
        return new VariantBuilder(
            query: $query
        );
    }

    protected static function newFactory(): VariantFactory {
        return new VariantFactory;
    }
}
