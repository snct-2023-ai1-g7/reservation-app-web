<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReserveRequest;
use App\Services\ReserveService;
use Exception;
use Illuminate\Http\JsonResponse;

class ReserveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReserveRequest $request, ReserveService $reserveService) : JsonResponse
    {
        $user = $request->user();
        $start = $request->start();
        $end = $request->end();
        
        try {
            $reserveService->reserve($start, $end, $user->room_number);
        } catch (Exception $e) {
            return new JsonResponse([
                "data" => [
                    "message" => $e->__toString()
                ]
            ]); 
        }

        return new JsonResponse([
            "data" => [
                "message" => "Reservation succeeded."
            ]
        ]);
    }
}