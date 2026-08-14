<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * PreserveRawBody
 *
 * Captures the raw request body as a request attribute BEFORE Laravel's
 * JSON parsing middleware consumes it. This is required so that webhook
 * signature verification can compute HMAC over the exact bytes received.
 *
 * Register on all payment webhook routes in the route definition.
 */
class PreserveRawBody
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Capture raw body before any parsing occurs.
        $request->attributes->set('raw_body', $request->getContent());

        return $next($request);
    }
}
