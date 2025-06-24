<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;

class SuperAdminLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            Log::info('SuperAdmin login attempt', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::guard('superadmin')->attempt($request->only('email', 'password'))) {
                Log::warning('SuperAdmin login failed', ['email' => $request->email]);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $superadmin = Auth::guard('superadmin')->user();

            // Generate token
            $token = $superadmin->createToken('superadmin-token')->plainTextToken;

            Log::info('SuperAdmin login successful', ['id' => $superadmin->id, 'email' => $superadmin->email]);

            return response()->json([
                'user' => $superadmin,
                'token' => $token,
            ]);

        } catch (ValidationException $e) {
            Log::error('Validation error during SuperAdmin login', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Unexpected error during SuperAdmin login', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function me()
    {
        return response()->json(Auth::guard('superadmin')->user());
    }
}
