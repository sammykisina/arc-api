<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Variants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Variants\StoreRequest;
use App\Http\Resources\Executive\VariantResource;
use Domains\Catalog\Actions\Variant\CheckForSimilarVariant;
use Domains\Catalog\Actions\Variant\CreateVariant;
use Domains\Catalog\Factories\VariantFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if (
            $request->get(key: 'name')
            && $request->get(key: 'measure')
            && CheckForSimilarVariant::handle(name:$request->get(key: 'name'), measure:$request->get(key: 'measure'))
        ) {
            return response()->json(
                data:[
                    'error' => 1,
                    'message' => 'A Similar Variant Exists.',
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }

        if ($newVariant = CreateVariant::handle(
            variantValueObject:VariantFactory::make(
                attributes:$request->validated()
            )
        )) {
            return response()->json(
                data:[
                    'error' => 0,
                    'message' => 'Variant Created Successfully.',
                    'variant' => new VariantResource(
                        resource: $newVariant
                    ),
                ],
                status: Http::CREATED()
            );
        }

        return response()->json(
            data:[
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
