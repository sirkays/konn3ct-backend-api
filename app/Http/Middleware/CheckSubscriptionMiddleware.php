<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckSubscriptionMiddleware
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
        if(Carbon::now()->diffInDays(Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription), false) < 0){
            return redirect('/pay');
        }
        return $next($request);
    }
}
