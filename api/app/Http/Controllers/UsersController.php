<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UsersController extends Controller
{
    public function __invoke(Request $request, UserService $userService)
    {
        return response()->json([
            'message' => 'all systems are a go',
            'users' => $userService->getUsers()
        ]);
    }
}
