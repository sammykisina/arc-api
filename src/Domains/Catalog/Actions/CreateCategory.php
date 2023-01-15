<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions;

use Domains\Catalog\Models\Category;
use Domains\Catalog\ValueObjects\CategoryValueObject;

class CreateCategory {
    public static function handle(CategoryValueObject $categoryValueObject): Category {
        return Category::create([
            'name' => $categoryValueObject->name,
            'description' => $categoryValueObject->description,
        ]);
    }
}
