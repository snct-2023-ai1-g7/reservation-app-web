<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;

class GetUsersController extends Controller
{
    public function __invoke(AdminService $adminService) : JsonResponse 
    {
        $users = $adminService->getUsers();

        return new JsonResponse([
            'users' => $users
        ]);
    }
}
