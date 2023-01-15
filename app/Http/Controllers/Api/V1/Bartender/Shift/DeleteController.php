<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Bartender\Shift;

use App\Http\Controllers\Controller;
use Domains\Bartender\Actions\Shift\ClearShift;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class DeleteController extends Controller {
    public function __invoke(): JsonResponse {
        if (ClearShift::handle()) {
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Shift ended.Nice rest',
                ],
                status: Http::ACCEPTED()
            );
        }

        return response()->json(
            data: [
                'error' => 1,
                'message' => '
                    Shift Not Ended.    
                    Please Ensure no pending bill payments or Unconfirmed Payments.
                ',
            ],
            status: Http::NOT_IMPLEMENTED()
        );
    }
}
