<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions;

use Domains\Catalog\Actions\Suppliers\UpdateSupplierNumberOfClosedDeals;
use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Illuminate\Support\Facades\DB;

class UpdateProcurement {
    public static function handle(array $attributes, Procurement $procurement): bool {
        return match ($attributes['status']) {
            ProcurementStatus::delivered()->label => UpdateProcurement::delivered(
                procurement: $procurement,
                attributes: $attributes,
            ),

            ProcurementStatus::cancelled()->label => UpdateProcurement::cancelled(
                procurement: $procurement,
                attributes: $attributes
            )
        };
    }

    private static function delivered(Procurement $procurement, array $attributes): bool {
        try {
            DB::beginTransaction();
            if ($procurement->item->form === ProcurementItemForms::singles()->label) {
                $procurement->update(attributes: $attributes);
            } else {
                $procurement->update(attributes: $attributes);
                $procurement->item()->update([
                    'number_of_pieces_in_form' => $attributes['number_of_pieces_in_form'],
                ]);
            }

            UpdateSupplierNumberOfClosedDeals::handle(supplier_id:$procurement->supplier_id);

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    private static function cancelled(Procurement $procurement, array $attributes): bool {
        if (
            $procurement->update(attributes: $attributes)
            // &&
            // Mail::to($procurement->supplier->email)->send(new RestockProcurementCancelled)
        ) {
            return true;
        }

        return false;
    }
}
