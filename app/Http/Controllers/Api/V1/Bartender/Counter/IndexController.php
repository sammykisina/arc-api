<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Bartender\Counter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bartender\CounterResource;
use Domains\Bartender\Models\Counter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse {
        $counters = QueryBuilder::for(
            subject: Counter::class
        )->allowedIncludes(
            includes:['shift', 'items']
        )->get();

        return response()->json(
            data: CounterResource::collection(
                resource:$counters
            ),
            status: Http::OK()
        );
    }
}
