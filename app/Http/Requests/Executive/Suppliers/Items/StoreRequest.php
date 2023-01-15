<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Suppliers\Items;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [];
    }

    public function withValidator($validator) {
        $validator->sometimes(['variants'], 'required|array|exists:variants,id', function ($input) {
            if ($input->variants) {
                return count($input->variants) > 0;
            }
        })->sometimes(['products'], 'required|array|exists:products,id', function ($input) {
            if ($input->products) {
                return count($input->products) > 0;
            }
        });
    }
}
