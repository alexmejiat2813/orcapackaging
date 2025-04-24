<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\HR\Users;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Display the login form. If the user is already authenticated, redirect to dashboard.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        if (session()->has('user_id')) {
            return redirect('/dashboard');
        }

        $view = view('login.login');
        return Response::noCache(response($view));
    }

    /**
     * Handle custom login request using user code and password in plain text.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginCustom(Request $request)
    {
        $credentials = $request->validate([
            'code'     => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Users::where('Users_Code', $credentials['code'])->first();

        if (!$user || $user->Users_Pwd !== $credentials['password']) {
            return back()->with('error', 'Invalid code or password.');
        }

        Auth::login($user);
        return redirect()->intended('/dashboard')->with('success', 'Login successful.');
    }

    /**
     * Alternative login method (not recommended if using loginCustom).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'Users_Code' => 'required|string',
            'Users_Pwd'  => 'required|string',
        ]);

        $user = Users::where('Users_Code', $request->Users_Code)->first();

        if (!$user || $user->Users_Pwd !== $request->Users_Pwd) {
            return back()->withErrors(['login_error' => 'Invalid credentials.']);
        }

        Auth::login($user);
        session(['user' => $user]);

        return redirect()->intended('/');
    }

    /**
     * Log out the currently authenticated user and destroy the session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout successful.');
    }
}
?>
