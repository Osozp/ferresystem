<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonated_by')) {
            return $next($request);
        }

        if ($request->user() && $request->user()->isSuperAdmin()) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder al área de Super Admin.');
    }
}
