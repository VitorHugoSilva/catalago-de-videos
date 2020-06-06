<?php

namespace App\Http\Middleware;

use Closure;

class AdditionalSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('X-Frame-Options','DENY');
        $response->header('Cache-Control', 'no-store, no-cache');
        return $response;
    }
}
