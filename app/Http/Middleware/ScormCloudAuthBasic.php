<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScormCloudAuthBasic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->headers->has('php-auth-user') || ! $request->headers->has('php-auth-pw')) {
            abort(401);
        }

        $credentials = [
            'email' => $request->headers->get('php-auth-user'),
            'password' => $request->headers->get('php-auth-pw'),
        ];

        if (Auth::attempt($credentials) === false) {
            abort(401);
        }

        return $next($request);
    }
}
