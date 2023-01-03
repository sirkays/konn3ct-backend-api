<?php

namespace App\Http\Middleware;

use App\Jobs\IpVisitFinderJob;
use App\Models\VisitLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            $email = Auth::user()->email;
        } else {
            $email = "0";
        }

        $agent = $request->userAgent();
//        $ip='105.112.58.16';
        $ip = $request->ip();

        $vs = VisitLog::where(['email' => $email, 'ip_address' => $ip, 'device' => $agent])->latest()->first();

        if (!$vs) {
            $vs = VisitLog::create([
                'email' => $email,
                'ip_address' => $ip,
                'device' => $agent
            ]);
            IpVisitFinderJob::dispatch($vs->makeHidden('email'));
        }

        $request->merge(["vs" => $vs]);

        return $next($request);
    }
}
