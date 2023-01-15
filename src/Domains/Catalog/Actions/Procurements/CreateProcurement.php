<?php

declare(strict_types=1);

namespace Domains\Catalog\Actions\Procurements;

use Carbon\Carbon;
use Domains\Catalog\Constants\AllowedItemTypes;
use Domains\Catalog\Constants\ProcurementItemForms;
use Domains\Catalog\Constants\ProcurementStatus;
use Domains\Catalog\Models\Procurement;
use Domains\Catalog\Models\ProcurementItem;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Catalog\ValueObjects\ProcurementValueObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CreateProcurement {
    public static function handle(ProcurementValueObject $procurementValueObject): ?Procurement {
        try {
            DB::beginTransaction();

            $procurement = Procurement::create([
                'status' => ProcurementStatus::pending()->label,
                'procurement_date' => Carbon::now()->toDateString(),
                'due_date' => Carbon::now()->addDays(5)->toDateString(),
                'supplier_id' => $procurementValueObject->supplier_id,
            ]);

            CreateProcurement::createProcurementItem(
                procurement_id:$procurement->id,
                procurementValueObject:$procurementValueObject
            );

            // Mail::to('sammy@gmail.com')->send(new RestockProcurement($procurement));

            DB::commit();

            return $procurement;
        } catch (\Throwable $th) {
            DB::rollBack();

            return null;
        }
    }

    private static function createProcurementItem(int $procurement_id, ProcurementValueObject $procurementValueObject): void {
        $procurement_item = $procurementValueObject->type === AllowedItemTypes::VARIANT->value
          ? Variant::query()->with('product')->where('id', $procurementValueObject->item_id)->first()
          : Product::query()->where('id', $procurementValueObject->item_id)->first();

        ProcurementItem::create([
            'name' => $procurementValueObject->type === AllowedItemTypes::VARIANT->value
              ? $procurement_item->product->name.'_'.$procurement_item->name
              : $procurement_item->name,
            'form' => $procurementValueObject->form,
            'form_quantity' => $procurementValueObject->form === ProcurementItemForms::singles()->label
              ? null
              : $procurementValueObject->form_quantity,
            'number_of_single_pieces' => $procurementValueObject->form === ProcurementItemForms::singles()->label
              ? $procurementValueObject->number_of_single_pieces
              : null,
            'measure' => $procurementValueObject->measure,
            'procurement_id' => $procurement_id,
            'item_id' => $procurementValueObject->item_id,
            'type' => $procurementValueObject->type,
        ]);
    }
}
