<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Si no hay usuario autenticado, mandar al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Si el rol del usuario no está en la lista de permitidos, denegar acceso
        if (!in_array(Auth::user()->rol, $roles)) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
