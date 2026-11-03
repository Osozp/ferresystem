<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Permite acceso si es 'owner' o Super Admin
        if ($user && ($user->role === 'owner' || $user->isSuperAdmin())) {
            return $next($request);
        }
        abort(403, 'No tienes permisos para gestionar operarios.');
    }
}
