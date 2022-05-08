<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "role" => $request->role
        ]);

        $user->save();
        $token = $user->createToken("access_token")->plainTextToken;

        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            "user" => new UserResource($user),
            "role" => $user->role
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => "wrong email or password"
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('access_token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            "user" => new UserResource($user),
            "role" => $user->role
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}


