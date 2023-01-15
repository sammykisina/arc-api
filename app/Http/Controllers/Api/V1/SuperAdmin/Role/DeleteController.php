<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Role;

use App\Http\Controllers\Controller;
use Domains\Shared\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Role $role): JsonResponse {
        if ($role->slug === 'super-admin') {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'You Cannot Delete Super-Admin Role.',
                ],
                status: Http::UNPROCESSABLE_ENTITY()
            );
        }

        // delete
        if ($role->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Role Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        // incase of error
        return new Response(
            content:[
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
