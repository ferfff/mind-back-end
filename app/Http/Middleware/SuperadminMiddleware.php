<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperadminMiddleware
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
        $superAdminRole = env('ROLE_SUPERADMIN');

        if (Auth::check() && Auth::user()->role == $superAdminRole) {
            return $next($request);
         }

         Log::error('Access unauthorized '. Auth::user());
         return response()->json([
             'status' => 'error',
             'message' => 'Unauthorized',
         ], 401);
    }
}
