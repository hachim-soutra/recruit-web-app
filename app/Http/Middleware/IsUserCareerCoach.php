<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserCareerCoach
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
        if (auth()->user() && auth()->user()->user_type !== "coach") {
            return abort(401);
        }
        return $next($request);
    }
}
