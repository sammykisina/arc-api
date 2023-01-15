<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Suppliers;

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
                'unique:suppliers,name',
            ],
            'location' => [
                'required',
                'string',
            ],
            'phone_number' => [
                'required',
                'string',
                'min:9',
                'max:10',
                'unique:suppliers,phone_number',
            ],
            'email' => [
                'required',
                'unique:suppliers,email',
            ],
        ];
    }
}
