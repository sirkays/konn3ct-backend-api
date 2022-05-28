<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class WebsocketAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('auth-token');
        if (empty($token)) {
            return response()->json(['message' => 'Authentication header is missing.'], 401);
        }
        $user = User::where('id', $token)->first();
        if (empty($user)) {
            return response()->json(['message' => 'Invalid authentication token provided.'], 401);
        }
        $next($request);
    }
}
