<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login The User
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserRequest $request): JsonResponse
    {
        try {
            if (! Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email & Password does not match with our record',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            // Revoke all tokens...
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'User successfully logged',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
