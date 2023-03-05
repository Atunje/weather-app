<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UsersController extends Controller
{
    public function __invoke(UserService $userService)
    {
        return response()->json([
            'message' => 'all systems are a go',
            'users' => $userService->getUsers()
        ]);
    }
}
