<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in',
            'data' => [
                'user' => new AuthResource($user),
                'token' => $token,
            ],
        ], 200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->successMessage('Successfully logged out', 200);
    }


    public function resetPassword(Request $request)
    {

        $request->validate([
            'old_password' => 'string|required|min:6',
            'new_password' => 'string|required|min:6'
        ]);

        $user = Auth::user();

        // check if password is valid
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->errorResponse("The provided password is incorrect.", 401);
        }

        // Step 4: Check if new password is different from old
        if (Hash::check($request->new_password, $user->password)) {
            return $this->errorResponse(
                'New password must be different from your current password.',
                409
            );
        }

        $user->tokens()->delete();

        $user->password = Hash::make($request->new_password);

        $user->save();

        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
            'data' => [
                'user' => new AuthResource($user),
                'token' => $newToken,
            ],
        ], 200);
    }
}
