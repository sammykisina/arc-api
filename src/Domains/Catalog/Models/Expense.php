<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\ExpenseFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'descriptions',
        'amount',
        'date',
        'spender_id',
        'authorize_id',
        'shift_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected static function newFactory(): ExpenseFactory {
        return new ExpenseFactory;
    }
}
