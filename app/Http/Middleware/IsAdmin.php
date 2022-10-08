<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if ($request->header('access_token')) {
            $admin = User::where('access_token', '=', $request->header('access_token'))->first();
            if ($admin) {
                if ($admin->role == 'admin' || $admin->role == 'super_admin') {
                    return $next($request);
                }
            } else {
                return response()->json([
                    'error'  => 'wrong access_token'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'no access token in header'
            ]);
        }
    }
}
