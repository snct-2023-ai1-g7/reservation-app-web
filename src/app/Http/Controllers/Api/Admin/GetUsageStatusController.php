<?php

namespace App\Http\Controllers\API\Admin;

use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class GetUsageStatusController extends Controller
{
    public function __invoke(AdminService $adminService) : JsonResponse
    {
        $message = $adminService->getUsageStatus();

        return new JsonResponse(
            $message
        );
    }
}
