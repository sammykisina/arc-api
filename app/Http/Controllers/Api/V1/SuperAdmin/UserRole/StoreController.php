<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\UserRole;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UserRole\StoreRequest;
use App\Http\Resources\SuperAdmin\EmployeeResource;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    /**
     * connecting the specified user to a new role
     *
     * @param  StoreRequest  $request
     * @param  User  $user
     * @return Response
     */
    public function __invoke(StoreRequest $request, User $user): JsonResponse {
        // find if the role exists
        $role = Role::find($request->get(key: 'role_id'));

        if (! $role) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'role not available',
                ],
                status: Http::NOT_FOUND()
            );
        }

        // if the role is available and its not among the current user roles
        if (! $user->roles()->find($request->get(key: 'role_id'))) {
            $user->roles()->attach($role);

            return response()->json(
                data: new EmployeeResource(
                    resource:  $user->load('roles')
                ),
                status: Http::CREATED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'employee has the role already or the something went wrong',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
