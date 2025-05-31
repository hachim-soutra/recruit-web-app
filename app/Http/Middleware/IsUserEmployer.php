<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserEmployer
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
        if (auth()->user() && auth()->user()->user_type !== "employer") {
            if ($request->wantsJson()) {
                return abort(401);
            } else {
                return redirect()->route('welcome');
            }
        }
        return $next($request);
    }
}
