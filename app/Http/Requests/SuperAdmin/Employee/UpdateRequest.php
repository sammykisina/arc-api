<?php

declare(strict_types=1);

namespace App\Http\Requests\SuperAdmin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'work_id' => [
                'string',
                'unique:users,work_id',
            ],
            'first_name' => [
                'string',
            ],
            'last_name' => [
                'string',
            ],
            'email' => [
                'email',
            ],
            'role' => [
                'string',
                'exists:roles,slug',
            ],
        ];
    }
}
