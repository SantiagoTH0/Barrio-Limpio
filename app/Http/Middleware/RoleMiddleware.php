<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:citizen') or ('role:official,crew')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->guest('/login');
        }
        $user = Auth::user();
        if (!$user) {
            return redirect()->guest('/login');
        }
        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            abort(403, 'No autorizado');
        }
        return $next($request);
    }
}