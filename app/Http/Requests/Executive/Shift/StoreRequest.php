<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Shift;

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
            'user_id' => [
                'required',
                'integer',
            ],
            'creator' => [
                'required',
                'string',
            ],
            'waiters' => [
                'required',
                'array',
                'min:2',
            ],
        ];
    }
}
