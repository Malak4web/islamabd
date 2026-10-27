<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (empty($roles)) {
            $roles = ['admin'];
        }

        $isAdmin = (method_exists($user, 'isAdmin') && $user->isAdmin()) 
            || $user->role === 'admin' 
            || $user->email === 'admin@eslamabdulghanidesigns.com' 
            || ($user->id === 1);

        if (in_array('admin', $roles) && $isAdmin) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized. This action requires administrator privileges.'
            ], 403);
        }

        return $next($request);
    }
}
