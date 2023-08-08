<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangeUsersPasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request, UserService $userService) : JsonResponse 
    {
        $user_id = $request->only(['user_id'])['user_id'];
        $newPassword = '';

        try {
            $newPassword = $userService->regenerateUsersPassword($user_id);
        } catch(Exception $e) {
            return new JsonResponse([
                'message' => 'Failed to regenerate password.',
                'exception' => $e->__toString()
            ]);
        }

        return new JsonResponse([
            'message' => 'Password regeneration succeeded.',
            'new_password' => $newPassword,
        ]);
    }
}
