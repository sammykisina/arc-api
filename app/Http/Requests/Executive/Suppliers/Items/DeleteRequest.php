<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Suppliers\Items;

use Domains\Catalog\Constants\AllowedItemTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(values: AllowedItemTypes::toArray()),
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes(['variant_id'], 'required|integer|exists:variants,id', function ($input): bool {
            return $input->type === AllowedItemTypes::VARIANT->value;
        })->sometimes(['product_id'], 'required|integer|exists:products,id', function ($input): bool {
            return $input->type === AllowedItemTypes::PRODUCT->value;
        });
    }
}
