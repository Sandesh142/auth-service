<?php

namespace App\Http\Controllers\Auth\Register\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;

class AdminRegisterController extends Controller
{
    public function initialRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $adminRole = Role::where('name', 'admin')->first();

            if (!$adminRole) {
                return response()->json([
                    'message' => 'Admin role not found. Please contact support.'
                ], 500);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $adminRole->id,
            ]);

            $token = $user->createToken('admin-token')->plainTextToken;

            return response()->json([
                'message' => 'Admin registered successfully.',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            Log::error('Public Admin Register Error', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Something went wrong during registration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('role'); 

        return response()->json([
            'user' => $user,
        ]);
    }
}
