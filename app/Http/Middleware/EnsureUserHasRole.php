<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $allowed = collect(explode(',', $roles))->map(fn($r) => trim($r))->filter()->all();
        $role = Auth::user()->role ?? null;

        if (!$role || (!in_array($role, $allowed, true))) {
            abort(403);
        }

        return $next($request);
    }
}
