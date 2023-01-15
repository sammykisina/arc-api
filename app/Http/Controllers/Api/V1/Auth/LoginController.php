<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use JustSteveKing\StatusCode\Http;

class LoginController extends Controller {
    public function __invoke(LoginRequest $request): JsonResponse {
        // find the user
        $user = User::query()->where('work_id', $request->get(key: 'work_id'))->first();

        // authenticate
        if (! $user || ! Hash::check(value: $request->get(key: 'password'), hashedValue: $user->password)) {
            // no user or wrong password
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Invalid credentials.Please Enter the Correct Employee ID and Password.',
                ],
                status: Http::NOT_FOUND()
            );
        }

        // delete the previous access tokens on login
        if (config(key: 'hydra.delete_previous_access_tokens_on_login')) {
            $user->tokens()->delete();
        }

        $role = $user->role()->pluck('slug')->all();
        $plainTextToken = $user->createToken('arc-api-token', $role)
            ->plainTextToken;

        return response()->json(
            data: [
                'error' => 0,
                'user_work_id' => $user->work_id,
                'token' => $plainTextToken,
                'role' => $role[0],
            ],
            status: Http::OK()
        );
    }
}
