<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Database\Factories\UserFactory;
use Domains\Bartender\Models\Shift;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'work_id',
        'first_name',
        'last_name',
        'email',
        'role_id',
        'active',
        'password',
        'created_by',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'work_id' => 'integer',
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         related: Role::class,
    //         table: 'user_roles'
    //     );
    // }

    public function role(): BelongsTo {
        return $this->belongsTo(
            related:Role::class,
            foreignKey: 'role_id'
        );
    }

    public function shifts(): BelongsToMany {
        return $this->belongsToMany(
            related: Shift::class,
            table:'shift_workers'
        );
    }

    protected static function newFactory(): UserFactory {
        return new UserFactory;
    }
}
