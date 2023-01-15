<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Procurements;

use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Constants\ProcurementItemForms;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(values: AllowedItemTypes::toArray()),
            ],
            'procurement_details.form' => [
                'required',
                'string',
                Rule::in(values: ProcurementItemForms::toLabels()),
            ],
            'procurement_details.measure' => [
                'required',
                'integer',
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes(
            'item_id',
            'required|integer|exists:variants,id',
            function ($input): bool {
                return $input->type === AllowedItemTypes::VARIANT->value;
            }
        )->sometimes(
            'item_id',
            'required|integer|exists:products,id',
            function ($input): bool {
                return $input->type === AllowedItemTypes::PRODUCT->value;
            }
        )->sometimes(
            'procurement_details.number_of_single_pieces',
            'required|integer',
            function ($input): bool {
                if (is_array($input->procurement_details) && array_key_exists('form', $input->procurement_details)) {
                    return $input->procurement_details['form'] === ProcurementItemForms::singles()->label;
                }

                return false;
            }
        )->sometimes(
            'procurement_details.form_quantity',
            'required|integer',
            function ($input): bool {
                if (is_array($input->procurement_details) && array_key_exists('form', $input->procurement_details)) {
                    return $input->procurement_details['form'] != ProcurementItemForms::singles()->label;
                }

                return false;
            }
        );
    }
}
