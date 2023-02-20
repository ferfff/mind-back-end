<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $user = auth()->user();

        if ($user->role == $superAdminRole) {
            return $next($request);
         }

         Log::error('Access unauthorized '. $user);
         return response()->json([
             'status' => 'error',
             'message' => 'Unauthorized',
         ], 401);
    }
}
