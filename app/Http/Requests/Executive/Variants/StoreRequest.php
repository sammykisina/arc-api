<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Variants;

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
            ],
            'cost' => [
                'integer',
                'required',
            ],
            'retail' => [
                'integer',
                'required',
            ],
            'stock' => [
                'required',
                'integer',
            ],
            'measure' => [
                'required',
                'integer',
            ],
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'vat' => [
                'required',
                'boolean',
            ],
        ];
    }
}
