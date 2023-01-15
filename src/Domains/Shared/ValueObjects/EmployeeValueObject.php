<?php

declare(strict_types=1);

namespace Domains\Shared\ValueObjects;

class EmployeeValueObject {
    public function __construct(
        public string $work_id,
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $password,
        public string $role
    ) {
    }

    public function toArray(): array {
        return [
            'work_id' => $this->work_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
        ];
    }
}
