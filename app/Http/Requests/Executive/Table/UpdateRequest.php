<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Table;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
                'unique:tables,name',
            ],
            'number_of_seats' => [
                'integer',
            ],
            'extendable' => [
                'boolean',
            ],
            'number_of_extending_seats' => [
                'required_if:extendable,true',
                'integer'
            ]
        ];
    }
}
