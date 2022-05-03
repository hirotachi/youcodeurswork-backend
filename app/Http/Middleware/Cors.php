<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $res = $next($request);

//        if (isset($_SERVER['HTTP_ORIGIN'])) {
        if ($request->header('Origin')) {
            $res->header("Access-Control-Allow-Origin", $_SERVER['HTTP_ORIGIN']);
            $res->header("Access-Control-Allow-Credentials", true);
            $res->header("Access-Control-Max-Age", 86400);
        }

// Access-Control headers are received during OPTIONS requests
        if ($request->isMethod('OPTIONS')) {

//            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) // may also be using PUT, PATCH, HEAD etc
            if ($request->server("HTTP_ACCESS_CONTROL_REQUEST_METHOD")) // may also be using PUT, PATCH, HEAD etc
            {
                $res->header("Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE, OPTIONS");
            }

//            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            if ($request->server("HTTP_ACCESS_CONTROL_REQUEST_HEADERS")) {
                $res->header("Access-Control-Allow-Headers", $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
            }
        }
        return $res;
    }
}
