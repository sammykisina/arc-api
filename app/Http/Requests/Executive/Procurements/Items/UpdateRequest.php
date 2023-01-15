<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Procurements\Items;

use Domains\Catalog\Constants\ProcurementItemForms;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Enum\Laravel\Rules\EnumRule;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'form' => [
                'string',
                new EnumRule(enum: ProcurementItemForms::class),
                Rule::in(values: ProcurementItemForms::toLabels()),
            ],
            'measure' => [
                'integer',
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes(
            'form_quantity',
            'required|integer',
            function ($input): bool {
                return $input->form === ProcurementItemForms::crate()->label;
            }
        )->sometimes(
            'form_quantity',
            'required|integer',
            function ($input): bool {
                return $input->form === ProcurementItemForms::box()->label;
            }
        )->sometimes(
            'form_quantity',
            'required|integer',
            function ($input): bool {
                return $input->form === ProcurementItemForms::pack()->label;
            }
        )->sometimes(
            'number_of_single_pieces',
            'required|integer',
            function ($input): bool {
                return $input->form === ProcurementItemForms::singles()->label;
            }
        );
    }
}
