<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Role\StoreRequest;
use App\Http\Resources\SuperAdmin\RoleResource;
use Domains\Shared\Actions\CreateRole;
use Domains\Shared\Factories\RoleFactory;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($role = CreateRole::handle(
            roleValueObject: RoleFactory::make(attributes: $request->validated())
        )) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Role Created Successfully.',
                    'role' => new RoleResource(resource: $role),
                ],
                status: Http::CREATED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.'
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
