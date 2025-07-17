<?php

namespace App\Http\Controllers\Web\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Using the User model as requested
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class AdminRegisterWebController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.admin-user.admin-register'); // Assuming this view path
    }

    /**
     * Handle an incoming registration request for the default 'web' guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Unique in 'users' table
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            // Create the User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => '2',
                'role' => 'admin',
            ]);

            // Log the user in after successful registration using the default 'web' guard
            Log::info('User registered successfully', ['user_id' => $user->id, 'email' => $user->email]);
            Auth::guard('web')->login($user);

            // Redirect to the admin dashboard after registration
            return redirect()->route('admin.dashboard')->with('status', 'Registration successful!');
        } catch (\Exception $e) {
            // Log the error with context
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Redirect back with an error message
            return back()->withErrors(['registration_error' => 'Registration failed. Please try again.'])
                        ->withInput();
        }
    }
}