<?php

namespace App\Http\Controllers\Web\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Using the User model as requested
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-user.admin-login');
    }

    /**
     * Handle an incoming admin authentication request using the 'web' guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate using the default 'web' guard
        // This guard uses the 'users' provider, which should point to App\Models\User
        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect to the admin dashboard after successful login
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application using the default 'web' guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout(); // Log out the web guard

        // REMOVED: $request->session()->invalidate();
        // REMOVED: $request->session()->regenerateToken();
        // These lines invalidate the entire session, logging out other guards.
        // Auth::guard('web')->logout() is sufficient to clear this guard's state.

        return redirect('/'); // Redirect to home page after logout
    }
    /**
     * Display the form to request a password reset link for admin users.
     * The view path is now consistent with your provided structure.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.admin-user.forgot-password');
    }

    /**
     * Send a password reset link to the given user's email using the 'users' password broker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // IMPORTANT: Use 'users' broker for password reset, as the 'web' guard uses the 'users' provider
        $response = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? back()->with('status', trans($response))
                    : back()->withErrors(['email' => trans($response)]);
    }

    /**
     * Display the password reset view for the given token.
     * The view path is now consistent with your provided structure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.admin-user.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password using the 'users' password broker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Use the 'users' password broker to reset the password
        $response = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Log the user in after reset using the default 'web' guard
                Auth::guard('web')->login($user);
            }
        );

        // Redirect to the admin dashboard after password reset
        return $response == Password::PASSWORD_RESET
                    ? redirect()->route('admin.dashboard')->with('status', trans($response))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => trans($response)]);
    }
}