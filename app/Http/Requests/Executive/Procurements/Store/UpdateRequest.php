<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Procurements\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'number_of_pieces_in_form' => [
                'required',
                // Rule::exists('users', 'id')->where(function ($query) {
                //     $query->where('admin_id', 1);
                // }),
            ],
        ];
    }
}
