<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Table;

use App\Http\Controllers\Controller;
use Domains\Fulfillment\Models\Table;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(Table $table) {
        if ($table->delete()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Table Deleted Successfully.',
                ],
                status:Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => 'something went wrong',
            ],
            status: Http::EXPECTATION_FAILED()
        );
    }
}
