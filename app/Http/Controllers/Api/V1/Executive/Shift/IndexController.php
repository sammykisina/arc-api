<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive\Shift;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ShiftResource;
use Domains\Bartender\Models\Shift;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller {
    public function __invoke(): JsonResponse {
        $shifts = QueryBuilder::for(
            subject: Shift::class
        )->allowedIncludes(
            includes:['workers.role']
        )->latest()->get();

        return response()->json(
            data: ShiftResource::collection(
                resource: $shifts
            ),
            status: Http::OK()
        );
    }
}
