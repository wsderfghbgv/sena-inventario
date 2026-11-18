<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Valida si el usuario autenticado tiene el permiso requerido (RF-022).
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $user = Auth::user();

        if (!$user->estaActivo()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Tu cuenta se encuentra inactiva.');
        }

        if (!$user->tienePermiso($permission)) {
            abort(403, "Acceso bloqueado: No cuentas con el permiso [{$permission}] para realizar esta acción.");
        }

        return $next($request);
    }
}
