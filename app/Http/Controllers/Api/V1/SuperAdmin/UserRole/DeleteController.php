<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\UserRole;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuperAdmin\EmployeeResource;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    /**
     * Delete a specified role from a specified user
     *
     * @param  User  $user
     * @param  Role  $role
     * @return JsonResponse
     */
    public function __invoke(User $user, Role $role): JsonResponse {
        $user->roles()->detach($role);

        return response()->json(
            data: new EmployeeResource(
                resource: $user->load('roles')
            ),
            status: Http::ACCEPTED()
        );
    }
}
