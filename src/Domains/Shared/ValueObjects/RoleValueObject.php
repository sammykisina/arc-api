<?php

declare(strict_types=1);

namespace Domains\Shared\ValueObjects;

class RoleValueObject {
    /**
     * [Description for __construct]
     *
     * @param  public string $name
     * @param  public string $slug
     */
    public function __construct(
        public string $name,
        public string $slug
    ) {
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
