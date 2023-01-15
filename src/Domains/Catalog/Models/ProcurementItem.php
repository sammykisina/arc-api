<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\ProcurementItemFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementItem extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'name',
        'form',
        'form_quantity',
        'number_of_pieces_in_form',
        'number_of_single_pieces',
        'measure',
        'item_id',
        'type',
        'added_to_store',
        'procurement_id',
    ];

    protected $cast = [
        'added_to_store' => 'boolean',
    ];

    public function procurement(): BelongsTo {
        return $this->belongsTo(
            related: Procurement::class,
            foreignKey:'procurement_id'
        );
    }

    protected static function newFactory(): ProcurementItemFactory {
        return new ProcurementItemFactory;
    }
}
