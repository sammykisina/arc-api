<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Tokens;

use App\Http\Controllers\Controller;
use Domains\Catalog\Models\Token;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Token $token): JsonResponse {
        if ($token->approved && !$token-> added_to_store) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Cannot delete this Token Yet.Its Items Are Not Added to Store."
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        if ($token->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => "Token Deleted Successfully."
                ],
                status: Http::ACCEPTED()
            );
        }
    }
}
