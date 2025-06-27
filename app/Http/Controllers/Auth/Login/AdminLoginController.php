<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found. Please register first.'], 404);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();

            if ($user->role->name !== 'admin') {
                return response()->json(['message' => 'Access denied for non-admin users'], 403);
            }

            $token = $user->createToken('admin-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during login.',
                'error'   => $e->getMessage()  // optional: remove in production
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
