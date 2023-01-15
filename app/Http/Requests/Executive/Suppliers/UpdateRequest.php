<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Suppliers;

use Domains\Catalog\Constants\SuppliersStatus;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'string',
                'unique:suppliers,name',
            ],
            'location' => [
                'string',
            ],
            'phone_number' => [
                'string',
                'min:9',
                'max:10',
            ],
            'email' => [
                'unique:suppliers,email',
            ],
            'status' => [
                new EnumRule(enum:SuppliersStatus::class),
            ],
            'type' => [
                'string',
            ],
            'items' => [
                'array',
            ],
        ];
    }
}
