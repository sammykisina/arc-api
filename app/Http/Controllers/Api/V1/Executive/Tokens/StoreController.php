<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Tokens;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Tokens\StoreRequest;
use App\Http\Resources\Executive\TokenResource;
use Domains\Catalog\Actions\GetItem;
use Domains\Catalog\Actions\Tokens\CheckIfTokenMeasureIsSameAsTokenItem;
use Domains\Catalog\Actions\Tokens\CreateToken;
use Domains\Catalog\Factories\TokenFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($request->get(key: 'item_id')) {
            if (!GetItem::handle(
                item_id: $request->get(key: 'item_id'),
                item_type: $request->get(key: 'item_type'),
            )) {
                return response()->json(
                    data: [
                        'error' => 1,
                        'measure' => "The Selected Item Is Not Found."
                    ],
                    status: Http::NOT_FOUND()
                );
            }

            if (!CheckIfTokenMeasureIsSameAsTokenItem::handle(
                token_measure: $request->get(key: 'measure'),
                item_id: $request->get(key: 'item_id'),
                item_type: $request->get(key: 'item_type'),
            )) {
                return response()->json(
                    data: [
                        'error' => 0,
                        'measure' => "The Token and The Item It's Selected For Are Incompatible."
                    ],
                    status: Http::UNPROCESSABLE_ENTITY()
                );
            }
        }

        if ($token = CreateToken::handle(
            tokenValueObject: TokenFactory::make($request->validated())
        )) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => "Token Created Successfully.",
                    'token' => new TokenResource(resource: $token)
                ],
                status: Http::CREATED()
            );
        }

        return response()->json(
            data: [
                'error' => 0,
                'message' => "Something Went Wrong.",
            ],
            status: Http::CREATED()
        );
    }
}
