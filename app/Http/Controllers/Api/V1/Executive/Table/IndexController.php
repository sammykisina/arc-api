<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Table;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\TableResource;
use Domains\Fulfillment\Models\Table;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $tables = QueryBuilder::for(
            subject: Table::class,
        )->latest()->get();

        return response()->json(
            data: TableResource::collection(
                resource: $tables
            ),
            status: Http::OK()
        );
    }
}
