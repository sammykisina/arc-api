<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Executive\Suppliers\UpdateRequest;
use Domains\Catalog\Models\Supplier;
use JustSteveKing\StatusCode\Http;

class UpdateController extends Controller {
    public function __invoke(UpdateRequest $request, Supplier $supplier) {
        if ($supplier->update($request->validated())) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Supplier Updated Successfully.',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'Something Went Wrong.',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
