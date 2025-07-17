<?php

namespace App\Http\Controllers\Web\Auth\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin\SuperAdmin; // Correct: Use the SuperAdmin model

class SuperAdminAuthController extends Controller
{
    /**
     * Show the SuperAdmin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Corrected view path based on your provided directory structure
        return view('auth.superadmin.superadmin-login');
    }

    /**
     * Handle a SuperAdmin login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the user in the 'super_admins' table using the SuperAdmin model
        $user = SuperAdmin::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Before logging in the SuperAdmin, log out any other active guards

            // Log in the user using the 'superadmin' guard
            Auth::guard('superadmin')->login($user, $request->remember);

            // Redirect to the intended URL or the superadmin dashboard
            return redirect()->intended(route('superadmin.dashboard'));
        }

        // If login fails, redirect back with errors
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not a SuperAdmin.',
        ])->onlyInput('email');
    }

    /**
     * Log the SuperAdmin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout(); // Log out the superadmin guard

        // REMOVED: $request->session()->invalidate();
        // REMOVED: $request->session()->regenerateToken();
        // These lines invalidate the entire session, logging out other guards.
        // Auth::guard('superadmin')->logout() is sufficient to clear this guard's state.

        return redirect()->route('superadmin.login'); // Redirect to the superadmin login page
    }
    /**
     * Show the password reset request form for SuperAdmin.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        // Corrected view path based on your provided directory structure
        return view('auth.superadmin.superadmin-forgot-password');
    }

    /**
     * Send the password reset link to the SuperAdmin's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find the user in the 'super_admins' table using the SuperAdmin model
        $user = SuperAdmin::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No SuperAdmin account found with that email address.']);
        }

        // Generate a password reset token and send notification
        $token = app('auth.password.broker')->createToken($user);
        $user->sendPasswordResetNotification($token);

        return back()->with('status', 'We have emailed your password reset link!');
    }

    /**
     * Show the password reset form for SuperAdmin.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm($token)
    {
        // Corrected view path based on your provided directory structure
        return view('auth.superadmin.superadmin-reset-password', ['token' => $token]);
    }

    /**
     * Reset the SuperAdmin's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $response = app('auth.password.broker')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Ensure the user being reset is an instance of SuperAdmin model
                if (!$user instanceof SuperAdmin) {
                    abort(403, 'Unauthorized password reset attempt.');
                }
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => null,
                ])->save();
            }
        );

        return $response == app('auth.password.broker')->PASSWORD_RESET
            ? redirect()->route('superadmin.login')->with('status', 'Your password has been reset!')
            : back()->withErrors(['email' => trans($response)]);
    }
}
