<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Role\UpdateRequest;
use Domains\Shared\Models\Role;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Role $role): JsonResponse {
        if ($request->get(key: 'slug') && ($role->slug === 'super-admin' || $role->slug === 'admin')) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Cannot Edit Super-Admin or Admin Role.',
                ],
                status: Http::NOT_MODIFIED()
            );
        }

        if ($role->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Role Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        // error return
        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something went wrong',
            ],
            status: Http::NOT_MODIFIED()
        );
    }
}
