<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class HasTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (request()->bearerToken()) {
            $token = PersonalAccessToken::findToken(request()->bearerToken());
            auth()->login($token->tokenable);
        }
        return $next($request);
    }
}
