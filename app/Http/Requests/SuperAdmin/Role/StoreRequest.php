<?php

declare(strict_types=1);

namespace App\Http\Requests\SuperAdmin\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'required',
                'string',
                'unique:roles,name',
            ],
            'slug' => [
                'required',
                'unique:roles,slug',
            ],
        ];
    }
}
