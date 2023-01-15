<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\TokenFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'number_of_single_pieces',
        'measure',
        'owner',
        'added_to_store',
        'item_id',
        'item_type',
        'approved'
    ];

    protected $casts = [
        'added_to_store' => 'boolean'
    ];

    protected static function newFactory(): TokenFactory {
        return new TokenFactory;
    }
}
