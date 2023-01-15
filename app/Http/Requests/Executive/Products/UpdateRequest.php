<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
                'unique:products,name',
            ],
            'cost' => [
                'integer',
            ],
            'retail' => [
                'integer',
            ],
            'stock' => [
                'integer',
            ],
            'store' => [
                'integer',
            ],
            'measure' => [
                'integer',
            ],
            'category_id' => [
                'integer',
                'exists:categories,id',
            ],
            'vat' => [
                'boolean',
            ],
        ];
    }
}
