<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Role;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuperAdmin\RoleResource;
use Domains\Shared\Models\Role;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $roles = QueryBuilder::for(
            subject: Role::class
        )->allowedIncludes(
            includes: ['users']
        )->get();

        return response()->json(
            data: RoleResource::collection(
                resource: $roles
            ),
            status: Http::OK()
        );
    }
}
