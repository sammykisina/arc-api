<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Variants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
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
            'product_id' => [
                'integer',
                'exists:products,id',
            ],
            'vat' => [
                'boolean',
            ],
        ];
    }
}
