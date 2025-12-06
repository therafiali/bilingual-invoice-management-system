<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(RegisterRequest $request)
    {
        $user =  $this->authService->createUser($request->validated());

        return $this->successResponse(
            new AuthResource($user),
            "User Successfully Register",
            201
        );
    }

    public function login(LoginRequest $request)
    {

        $isValid = Auth::attempt($request->validated());

        if (!$isValid) {
            return $this->errorResponse("Invalid Credientials", 401, "Invalid email or password");
        }

        $user = Auth::user();

        return $this->successResponse(new AuthResource($user), "Successfully Login", 200);
    }
}
