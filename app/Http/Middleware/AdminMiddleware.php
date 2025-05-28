<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar primero si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Luego verificar si es administrador
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return $next($request);
    }
}
