<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\HR\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;

class LoginController extends Controller
{
    public function showLoginForm()
{
    if (session()->has('user_id')) {
        return redirect('/dashboard'); // o cualquier ruta por defecto
    }

    $view = view('login.login');

    return Response::noCache(response($view));

}


    public function loginCustom(Request $request)
{
    $credentials = $request->validate([
        'code' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = Users::where('Users_Code', $credentials['code'])->first();

    if (!$user || $user->Users_Pwd !== $credentials['password']) {
        return back()->with('error', 'Código o contraseña incorrecta.');
    }

    // ✅ Autenticar usando Auth
    Auth::login($user);
   //dd(Auth::user()); 


   return redirect()->intended('/dashboard')->with('success', 'Inicio de sesión exitoso');
}

    public function login(Request $request)
    {
        $request->validate([
            'Users_Code' => 'required|string',
            'Users_Pwd' => 'required|string',
        ]);

        $user = Users::where('Users_Code', $request->Users_Code)->first();

        if (!$user || $user->Users_Pwd !== $request->Users_Pwd) {
            return back()->withErrors(['login_error' => 'Invalid credentials.']);
        }

        // Autenticación manual
        Auth::login($user);
        Session::put('user', $user);

        return redirect()->intended('/');
    }

    public function logout(): RedirectResponse
{
    Auth::logout(); // Cierra la sesión

    request()->session()->invalidate(); // Invalida la sesión actual
    request()->session()->regenerateToken(); // Regenera el token CSRF

    return redirect('/login')->with('success', 'Sesión cerrada correctamente');
}
} 
