<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        $userRoles = Auth::user()->roles->pluck('name');
        // dd($userRoles->contains('admin'));

        if (!$userRoles->contains('admin')) {
            return response()->json(['message' => "Unauthorized access"]);
        }

        return $next($request);
    }
}
