<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(AuthRequest $request)
    {
       $user =  $this->authService->createUser($request->validated());

        return $this-> successResponse(
                new AuthResource($user), "User Successfully Register", 201
        );
    }

}
