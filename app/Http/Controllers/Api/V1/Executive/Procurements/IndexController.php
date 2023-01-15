<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Procurements;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\ProcurementResource;
use Domains\Catalog\Models\Procurement;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $procurements = QueryBuilder::for(
            subject: Procurement::class
        )->allowedIncludes(
            includes:['supplier', 'item']
        )->latest()->get();

        return response()->json(
            data: ProcurementResource::collection(
                resource: $procurements
            ),
            status: Http::OK()
        );
    }
}
