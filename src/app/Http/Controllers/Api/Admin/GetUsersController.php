<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUsersController extends Controller
{
    public function __invoke(UserService $userService) : JsonResponse 
    {
        $users = $userService->getUsers();

        return new JsonResponse([
            'data' => [
                'users' => $users
            ]
        ]);
    }
}
