<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Employee;

use App\Http\Controllers\Controller;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(User $user): JsonResponse {
        $userRole = $user->role->slug;
        if ($userRole === 'super-admin') {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'You Cannot Delete The Super Admin.',
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }

        if ($user->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Employee Deleted Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_MODIFIED()
        );
    }
}
