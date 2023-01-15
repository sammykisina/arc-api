<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Table\StoreRequest;
use App\Http\Resources\Executive\TableResource;
use Domains\Fulfillment\Actions\CreateTable;
use Domains\Fulfillment\Factories\TableFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($table = CreateTable::handle(
            TableFactory::make(
                attributes: $request->validated()
            )
        )) {
            return response()->json(
                data:[
                    'error' => 0,
                    'message' => 'Table Created Successfully.',
                    'table' => new TableResource(resource: $table),
                ],
                status: Http::CREATED()
            );
        }

        return response()->json(
            data:[
                'error' => 0,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
