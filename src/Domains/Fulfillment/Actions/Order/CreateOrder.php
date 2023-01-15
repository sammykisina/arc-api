<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Actions\Order;

use Domains\Bartender\Models\Shift;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Fulfillment\Models\Order;
use Domains\Fulfillment\Models\Orderline;
use Domains\Fulfillment\Status\OrderStatus;
use Illuminate\Support\Facades\DB;

class CreateOrder {
    public static function handle(float $sub_total, float $discount, float $total, array $orderline_data, int|null $table_id) {
        try {
            DB::beginTransaction();
            $activeShift = Shift::query()
        ->where('active', true)
        ->first();

            /**
             * create order
             */
            $order = Order::create([
                'number' => Order::generateOrderNumber(active_shift_id: $activeShift->id),
                'status' => OrderStatus::pending()->label,
                'sub_total' => $sub_total,
                'discount' => $discount,
                'total' => $total,
                'table_id' => $table_id,
                'shift_id' => $activeShift->id,
                'completed_at' => null,
                'cancelled_at' => null,
            ]);

            /**
             * create orderline
             */
            foreach ($orderline_data as $orderline) {
                $item = null;
                if ($orderline['form'] === 'Product') {
                    $item = Product::query()->where('id', $orderline['product_id'])->first();
                } else {
                    $item = Variant::query()->where('id', $orderline['product_id'])->first();
                }

                Orderline::create([
                    'name' => $orderline['name'],
                    'cost' => $item->cost,
                    'retail' => $item->retail,
                    'quantity' => $orderline['quantity'],
                    'order_id' => $order->id,
                ]);
            }

            DB::commit();

            return $order;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;

            return;
        }
    }
}
