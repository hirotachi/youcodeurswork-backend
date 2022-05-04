<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = explode('|', $role);
        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'You are not authorized to access this resource.',
            ], 403);
        }
        return $next($request);
    }
}
