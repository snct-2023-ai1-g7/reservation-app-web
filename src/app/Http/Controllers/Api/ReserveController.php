<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReserveRequest;
use App\Models\Reserve;
use Illuminate\Http\JsonResponse;

class ReserveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReserveRequest $request) : JsonResponse
    {
        $user = $request->user();
        $start = $request->start();
        $end = $request->end();

        $reserve = Reserve::where('start', $start->format("H:i:s"))
                    ->where('end', $end->format("H:i:s"))
                    ->first();

        if(is_null($reserve->room_number)) {
            $reserve->update(['room_number' => $user->room_number]);
        } else {
            return new JsonResponse([
                "data" => [
                    "message" => "Another room has already reserved."
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