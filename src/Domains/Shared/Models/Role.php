<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Database\Factories\RoleFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
    ];

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    // public function users(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         related: User::class,
    //         table:  'user_roles'
    //     );
    // }

    public function users(): HasMany {
        return $this->hasMany(
            related: User::class,
            foreignKey:'role_id'
        );
    }

    protected static function newFactory(): RoleFactory {
        return new RoleFactory;
    }
}
