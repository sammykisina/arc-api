<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Tokens;

use Domains\Catalog\Constants\AllowedItemTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'required',
                'string',
                'unique:tokens,name'
            ],
            'number_of_single_pieces' => [
                'integer',
                'required'
            ],
            'measure' => [
                'required',
                'integer'
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes('item_id', 'integer', function ($input): bool {
            if ($input->item_id) {
                return true;
            }

            return false;
        })->sometimes('item_type', [
            'required',
            Rule::in(values: AllowedItemTypes::toArray())
        ], function ($input): bool {
            if ($input->item_id) {
                return true;
            }

            return false;
        });
    }
}
