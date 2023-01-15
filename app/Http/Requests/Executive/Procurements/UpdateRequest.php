<?php

declare(strict_types=1);

namespace App\Http\Requests\Executive\Procurements;

use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class UpdateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'status' => [
                'required',
                new EnumRule(enum: ProcurementStatus::class),
            ],
            'supplier_id' => [
                'integer',
                'exists:suppliers,id',
            ],
        ];
    }

    public function withValidator($validator): void {
        $validator->sometimes(
            'delivered_date',
            'required|date',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label;
            }
        )->sometimes(
            'total_cost',
            'required|integer',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label;
            }
        )->sometimes(
            'procurement_uuid',
            'required|string',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label;
            }
        )->sometimes(
            'number_of_pieces_in_form',
            'required|integer',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label
                 &&
                Procurement::query()->where('uuid', $input->procurement_uuid)->first()->item->form === ProcurementItemForms::box()->label;
            }
        )->sometimes(
            'number_of_pieces_in_form',
            'required|integer',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label
                 &&
                Procurement::query()->where('uuid', $input->procurement_uuid)->first()->item->form === ProcurementItemForms::crate()->label;
            }
        )->sometimes(
            'number_of_pieces_in_form',
            'required|integer',
            function ($input): bool {
                return $input->status === ProcurementStatus::delivered()->label
                 &&
                Procurement::query()->where('uuid', $input->procurement_uuid)->first()->item->form === ProcurementItemForms::pack()->label;
            }
        )->sometimes(
            'cancelled_date',
            'required|date',
            function ($input): bool {
                return $input->status === ProcurementStatus::cancelled()->label;
            }
        );
    }
}
