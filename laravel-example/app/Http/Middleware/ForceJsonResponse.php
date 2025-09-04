<?php

namespace App\Http\Middleware;
// Usados mayormente para autentificacion, verificacion y evito de robos, logs

// Usado para continuar con el flujo de trabajo

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }
        
        return $next($request);
    }
}
