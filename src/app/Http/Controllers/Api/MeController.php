<?php declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request) : JsonResponse
    {
        $user = $request->user();

        return new JsonResponse([
            'user_id' => $user->user_id,
            'user_type' => $user->user_type,
            'room_number' => $user->room_number,
        ]);
    }
}
