<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Admin\UpdateUsageStatusRequest;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class UpdateUsageStatusController extends Controller
{
    public function __invoke(UpdateUsageStatusRequest $updateRequest, AdminService $adminService) : JsonResponse
    {
        $action = $updateRequest->action();
        $room_number = $updateRequest->room_number();

        if($action === 'toUsing') {
            $message = $adminService->updateToUsing($room_number);
            return new JsonResponse(
                $message
            );
        } else if($action === 'toLeft') {
            $message = $adminService->updateToLeft($room_number);
            return new JsonResponse(
                $message
            );
        } else {
            return new JsonResponse([
                'message' => 'Invalid action.'
            ]);
        }

    }
}
