<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Employee\UpdateRequest;
use Domains\Shared\Actions\UpdateEmployee;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, User $user): JsonResponse {
        $loggedInUser = auth()->user();
        if ($loggedInUser->uuid === $user->uuid || $loggedInUser->tokenCan('super-admin')) {
            $superAdminRole = Role::query()->where('slug', 'super-admin')->first();

            if ($user->role_id === $superAdminRole->id && ($request->exists('role') || $request->exists('work_id'))) {
                return response()->json(
                    data: [
                        'error' => 1,
                        'message' => "You Cannot Edit Super Admin's Role or Work ID.",
                    ],
                    status: Http::NOT_ACCEPTABLE()
                );
            }

            if (UpdateEmployee::handle(
                data: $request->validated(),
                user: $user
            )) {
                return response()->json(
                    data: [
                        'error' => 0,
                        'message' => 'Employee Details Updated Successfully.',
                    ],
                    status: Http::ACCEPTED()
                );
            }
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
