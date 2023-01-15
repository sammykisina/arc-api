<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Tokens\Store;

use App\Http\Controllers\Controller;
use Domains\Catalog\Actions\Tokens\UpdateProductOrVariantStoreQuantity;
use Domains\Catalog\Models\Token;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(Token $token): JsonResponse {
        if (!$token->approved) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Update Token Item pieces When The Token is Not Yet Approved.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (!$token->item_id) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Please Create The Item Associated To This Token First.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if (UpdateProductOrVariantStoreQuantity::handle(token: $token)) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Items Store Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
