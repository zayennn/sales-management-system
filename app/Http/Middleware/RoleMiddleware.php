<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if ($role === 'admin' && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($role === 'petugas' && !auth()->user()->isPetugas()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}