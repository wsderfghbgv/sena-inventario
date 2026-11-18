<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        $user = Auth::user();

        // Verificar que el usuario esté activo
        if (!$user->estaActivo()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Tu cuenta se encuentra inactiva. Contacta al administrador.');
        }

        // Si no se especificaron roles, solo se requiere estar autenticado y activo
        if (empty($roles)) {
            return $next($request);
        }

        // Si se especificaron roles, verificar si el usuario tiene alguno de ellos
        // Soporta formatos como 'admin,instructor' o argumentos separados 'admin', 'instructor'
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $subRole) {
                $allowedRoles[] = trim($subRole);
            }
        }

        if (!in_array($user->rol, $allowedRoles, true)) {
            abort(403, 'No tienes permisos suficientes para acceder a este recurso.');
        }

        return $next($request);
    }
}
