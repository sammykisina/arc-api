<?php

declare(strict_types=1);

namespace Domains\Bartender\Models;

use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ShiftWorkers extends Model {
    use HasUuid;

    protected $fillable = [
        'uuid',
        'shift_id',
        'user_id',
    ];
}
