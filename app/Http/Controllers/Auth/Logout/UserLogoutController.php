<?php

namespace App\Http\Controllers\Auth\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully.'
            ]);
        }

        return response()->json([
            'message' => 'No authenticated user found.'
        ], 401);
    }
}
