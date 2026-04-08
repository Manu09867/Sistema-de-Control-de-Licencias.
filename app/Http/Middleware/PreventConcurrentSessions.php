<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PreventConcurrentSessions
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionActual = $request->session()->getId();
            $userId = Auth::id();

            // Buscar otras sesiones del mismo usuario
            $otrasSesiones = DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $sessionActual)
                ->exists();

            if ($otrasSesiones) {
                // Cerrar sesión y redirigir con mensaje
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')
                    ->withErrors(['general' => 'Esta cuenta ya tiene una sesión activa en otro dispositivo']);
            }
        }

        return $next($request);
    }
}