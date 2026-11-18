<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('productos.index');
        }

        return view('auth.login');
    }

    /**
     * Procesa la autenticación del usuario (RF-020).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // RF-020: Solo usuarios habilitados (activos) acceden
            if (!$user->estaActivo()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Tu cuenta se encuentra inactiva. Contacta al administrador del sistema.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('productos.index'))
                ->with('success', '¡Bienvenido(a), ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario (RF-020).
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
