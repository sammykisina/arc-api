<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Table\UpdateRequest;
use Domains\Fulfillment\Models\Table;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Table $table): JsonResponse {
        $additional_edit_data = [];
        if (key_exists(key: 'extendable', array:$request->validated())) {
            if (!$request->get(key: 'extendable')) {
                $additional_edit_data = ["number_of_extending_seats" => null];
            }
        }

        if ($table->update(array_merge($request->validated(), $additional_edit_data))) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Table Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something went wrong.',
            ],
            status: Http::NOT_MODIFIED()
        );
    }
}
