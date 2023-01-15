<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
                'unique:categories,name',
            ],
            'description' => [
                'string',
            ],
        ];
    }
}
