<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use function Symfony\Component\VarDumper\Dumper\esc;

class EnsureUserIsSubscribed
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
        if ($request->user()) {
            $count = Subscription::where('user_id', $request->user()->id)->count();
            if ($count == 0) {
                return redirect()->to('subscription')->withSuccess("You must be subscribe first to create a job post.");
            } elseif (!checkSubscriptionBalanceIsValid($request->user()->id)) {
                return redirect()->to('subscription')->withSuccess("You have consumed the number of job posts that you have purchased.");
            } 
            return $next($request);
        } else {
            return redirect()->to('');
        }
    }
}
