<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Table;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
                'required',
                'unique:tables,name',
            ],
            'number_of_seats' => [
                'integer',
                'required',
            ],
            'extendable' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function withValidator($validator) {
        $validator->sometimes(['number_of_extending_seats'], 'required|integer', function ($input) {
            return $input->extendable === true;
        });
    }
}
