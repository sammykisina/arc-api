<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuperAdmin\EmployeeResource;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $employees = QueryBuilder::for(
            subject: User::class
        )->allowedIncludes(
            includes: ['role']
        )->get();

        return response()->json(
            data: EmployeeResource::collection(
                resource: $employees
            ),
            status: Http::OK()
        );
    }
}
