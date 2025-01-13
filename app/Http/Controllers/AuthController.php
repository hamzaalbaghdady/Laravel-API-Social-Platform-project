<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:8|max:32|confirmed',
            'date_of_birth' => 'required|date',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required|min:8|max:32',
        ]);
        if (
            Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password']
            ])
        ) {
            $user = Auth::user();
            return response()->json([
                'data' => new UserResource($user),
                'access_token' => $user->createToken('api_token')->plainTextToken,
                'token_type' => 'Bearer'
            ]);
        }
        return response()->json(['message' => 'Invalid login information'], 401);
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out successfully!'
        ]);
    }
}
