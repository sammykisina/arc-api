<?php

declare(strict_types=1);

namespace Domains\Bartender\Models;

use Database\Factories\ShiftFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shift extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'total_amount',
        'creator',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function counter(): HasOne {
        return $this->hasOne(
            related: Counter::class,
            foreignKey: 'shift_id'
        );
    }

    public function workers(): BelongsToMany {
        return $this->belongsToMany(
            related: User::class,
            table:'shift_workers'
        );
    }

    /**
     * generate a shift name
     */
    public static function generateShiftName($work_id, $start_date, $end_date): string {
        return $work_id.' on '.$start_date.' to '.$end_date;
    }

    protected static function newFactory(): ShiftFactory {
        return new ShiftFactory;
    }
}
