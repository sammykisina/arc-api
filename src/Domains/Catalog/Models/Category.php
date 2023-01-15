<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\CategoryFactory;
use Domains\Shared\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'description',
    ];

    public function products(): HasMany {
        return $this->hasMany(
            related: Product::class,
            foreignKey: 'category_id'
        );
    }

    protected static function newFactory(): CategoryFactory {
        return new CategoryFactory;
    }
}
