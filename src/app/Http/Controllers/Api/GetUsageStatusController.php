<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetUsageStatusController extends Controller
{
    public function __invoke(Request $request, UserService $userService) : JsonResponse
    {
        $user = $request->user();
        $message = $userService->getUsageStatus($user);

        return new JsonResponse(
            $message
        );
    }
}
