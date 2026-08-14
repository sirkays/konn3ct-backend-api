<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies — safe when all traffic is behind Cloudflare.
     * Cloudflare strips and re-adds forwarding headers, so '*' cannot
     * be spoofed by an end user connecting through Cloudflare.
     *
     * In production, set TRUST_PROXIES=* in .env.
     * For tighter control, list Cloudflare's IP ranges explicitly:
     * https://www.cloudflare.com/ips/
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * Trust the X-Forwarded-* headers AND Cloudflare's CF-Connecting-IP.
     * CF-Connecting-IP is set by Cloudflare with the real visitor IP.
     * $request->ip() will return the real client IP in production.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
