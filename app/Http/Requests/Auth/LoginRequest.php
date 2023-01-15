<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'work_id' => [
                'int',
                'required',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
