<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Tokens;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Tokens\UpdateRequest;
use Domains\Catalog\Actions\GetItem;
use Domains\Catalog\Models\Token;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Token $token): JsonResponse {
        if ($request->get(key: 'added_to_store')) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Unprocessable Action."
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($request->get(key: 'approved') && auth()->user()->role->slug === "admin") {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Your Not Allowed To Approve A Token."
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($request->get(key: 'item_id') && !GetItem::handle(item_id:$request->get(key: 'item_id'), item_type:$request->get(key: 'item_type'))) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Please Ensure That The Token Item Exists."
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($token->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => "Token Updated Successfully."
                ],
                status: Http::ACCEPTED()
            );
        }
    }
}
