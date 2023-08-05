<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ReserveService;
use Illuminate\Http\JsonResponse;

class GetReservationsController extends Controller
{
    public function __invoke(ReserveService $reserveService) : JsonResponse
    {
        $reserves = $reserveService->getReserves();

        return new JsonResponse([
            'data' => [
                "reservations" => $reserves
            ],
        ]);
    }

}
