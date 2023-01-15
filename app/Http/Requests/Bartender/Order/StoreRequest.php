<?php

declare(strict_types=1);

namespace App\Http\Requests\Bartender\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'sub_total' => [
                'required',
                'numeric',
            ],
            'discount' => [
                'required',
                'numeric',
            ],
            'total' => [
                'required',
                'numeric',
            ],
            'orderline_data' => [
                'required',
                'array',
            ],
            'table_id' => [
                'integer',
            ],
        ];
    }
}
