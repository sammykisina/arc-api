<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Bartender\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bartender\OrderResource;
use Domains\Fulfillment\Models\Order;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $orders = QueryBuilder::for(
            subject: Order::class
        )->allowedIncludes(
            includes: ['items', 'table', 'payments']
        )->latest()->get();

        return response()->json(
            data: OrderResource::collection(
                resource: $orders
            ),
            status: Http::OK()
        );
    }
}
