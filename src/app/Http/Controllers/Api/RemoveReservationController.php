<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReserveRequest;
use App\Services\ReserveService;
use Exception;
use Illuminate\Http\JsonResponse;

class RemoveReservationController extends Controller
{
    public function __invoke(ReserveRequest $request, ReserveService $reserveService) : JsonResponse
    {
        $user = $request->user();
        $start = $request->start();
        $end = $request->end();
        
        try {
            $reserveService->removeReserve($start, $end, $user->room_number);
        } catch (Exception $e) {
            return new JsonResponse([
                "message" => $e->__toString()
            ]); 
        }

        return new JsonResponse([
            "message" => "Reservation cancelaration succeeded."
        ]);
    }  
}
