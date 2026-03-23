<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Si no está autenticado, redirigir al login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Verificar el rol
        if ($role === 'admin' && auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permisos de administrador');
        }

        if ($role === 'user' && auth()->user()->role !== 'user') {
            abort(403, 'Esta página es solo para usuarios normales');
        }

        return $next($request);
    }
}