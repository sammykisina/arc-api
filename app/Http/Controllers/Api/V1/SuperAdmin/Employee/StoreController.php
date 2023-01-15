<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\SuperAdmin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Employee\StoreRequest;
use App\Http\Resources\SuperAdmin\EmployeeResource;
use Domains\Shared\Actions\CreateEmployee;
use Domains\Shared\Factories\EmployeeFactory;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class StoreController extends Controller {
    public function __invoke(StoreRequest $request): JsonResponse {
        if ($request->get(key: 'role') === 'super-admin') {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'You Cannot Recreate The Super Admin.',
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }

        if ($request->get(key: 'role') === 'admin') {
            $adminRole = Role::query()->where('slug', 'admin')->first();
            $adminEmployee = User::query()->where('role_id', $adminRole->id)->first();
            if ($adminEmployee) {
                return response()->json(
                    data: [
                        'error' => 1,
                        'message' => 'You cannot Create More Than One Admins.You have Delete the exiting one to Create a new One.',
                    ],
                    status: Http::NOT_IMPLEMENTED()
                );
            }
        }

        // if user exits, about creation and report that user exits
        $user = User::query()->where('work_id', $request->get(key: 'work_id'))->first();
        if ($user) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Employee Exits.',
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }

        //create a user
        $newUser = CreateEmployee::handle(EmployeeFactory::make(attributes: $request->validated()));
        $employee = User::query()
            ->where('uuid', $newUser->uuid)
            ->with('role')
            ->first();

        if ($employee) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Employee Created Successfully.',
                    'employee' => new EmployeeResource(
                        resource: $employee
                    ),
                ],
                status: Http::CREATED()
            );
        }

        // if user not created
        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
