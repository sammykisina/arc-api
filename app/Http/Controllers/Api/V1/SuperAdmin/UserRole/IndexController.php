<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\UserRole;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuperAdmin\EmployeeResource;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class IndexController extends Controller {
    /**
     * Fetch all the roles associated with a specific user
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function __invoke(User $user): JsonResponse {
        return response()->json(
            data: new EmployeeResource(
                resource:$user->load('roles')
            ),
            status: Http::OK()
        );
    }
}
