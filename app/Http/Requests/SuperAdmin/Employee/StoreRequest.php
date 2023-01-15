<?php

declare(strict_types=1);

namespace App\Http\Requests\SuperAdmin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'work_id' => [
                'string',
                'required',
                'unique:users,work_id,except,id',
            ],
            'first_name' => [
                'string',
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'email' => [
                'email',
                'required',
                'unique:users,email',
            ],
            'password' => [
                'required',
            ],
            'role' => [
                'string',
                'required',
                'exists:roles,slug',
            ],
        ];
    }
}
