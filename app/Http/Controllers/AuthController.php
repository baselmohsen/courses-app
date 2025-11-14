<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|in:admin,instructor,student'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Create profile
        Profile::create([
            'user_id' => $user->id,
        ]);

        // Create token
        $token = $user->createToken("api_token")->plainTextToken;

        return response()->json([
            'user' => $user->load('profile'),
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($request->only('email','password'))) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }

        $user = auth()->user();

        // remove old tokens
        $user->tokens()->delete();

        $token = $user->createToken("api_token")->plainTextToken;

        return response()->json([
            'user' => $user->load('profile'),
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
