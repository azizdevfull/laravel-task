<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\RegisterUserRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->all());
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Register successfully!',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => "Notog'ri parol",
            ], 422);
        }
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successfully!',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }
}
