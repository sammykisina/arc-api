<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Tokens;

use App\Http\Controllers\Controller;
use App\Http\Resources\Executive\TokenResource;
use Domains\Catalog\Models\Token;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $tokens = QueryBuilder::for(
            subject: Token::class
        )->latest()->get();


        return response()->json(
            data:  TokenResource::collection(
                resource: $tokens
            ),
            status: Http::OK()
        );
    }
}
