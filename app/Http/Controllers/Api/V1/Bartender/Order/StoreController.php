<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Bartender\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bartender\Order\StoreRequest;
use App\Http\Resources\Bartender\OrderResource;
use Domains\Fulfillment\Actions\Order\CreateOrder;
use Domains\Fulfillment\Models\Order;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        $newOrder = CreateOrder::handle(
            sub_total: $request->get(key: 'sub_total'),
            discount: $request->get(key: 'discount'),
            total: $request->get(key: 'total'),
            orderline_data: $request->get(key: 'orderline_data'),
            table_id: $request->get(key: 'table_id')
        );

        $order = Order::query()
            ->where('id', $newOrder->id)
            ->with('items')
            ->first();

        if ($order) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'order created, waiting payment',
                    'order' => new OrderResource(
                        resource:$order,
                    ),
                ],
                status: Http::CREATED()
            );
        }
    }
}
