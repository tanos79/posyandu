<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            if ($user->role === 'kader') {
                return redirect()->route('kader.dashboard');
            } elseif ($user->role === 'orang_tua') {
                return redirect()->route('orang-tua.dashboard');
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
