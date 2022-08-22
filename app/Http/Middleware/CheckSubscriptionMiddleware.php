<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user()->plan!=1 && Auth::user()->subsciption!='new') {
            if (Carbon::now()->diffInDays(Carbon::parse(Auth::user()->subscription), false) < 0) {
                $user = User::where("user_name", Auth::id())->first();
                $user->plan = 1;
                $user->save();
                session(["error" => "Your subscription has expired, some of your rooms will no longer be visible. Kindly visit payment page to make payment."]);
            }
        }
        return $next($request);
    }
}
