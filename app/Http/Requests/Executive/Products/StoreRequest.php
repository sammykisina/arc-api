<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Products;

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
            'form' => [
                'required',
                'string',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes(['cost', 'retail', 'stock', 'measure'], 'required|integer', function ($input) {
            return $input->form === 'independent';
        })->sometimes('vat', 'required|boolean', function ($input) {
            return $input->form === 'independent';
        });
    }
}
