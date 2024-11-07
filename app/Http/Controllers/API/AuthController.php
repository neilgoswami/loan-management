<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'status' => 401
            ], 401);
        }

        return response()->json([
            'message' => 'Authenticated successfully.',
            'token' => $user->createToken('auth_token_' . $user->email)->plainTextToken
        ]);
    }
}
