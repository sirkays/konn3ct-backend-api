<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GeoLocationService
 *
 * Resolves the client's country and preferred currency using a priority chain:
 *   1. Cloudflare CF-IPCountry header (when TRUST_CLOUDFLARE_HEADERS=true)
 *   2. MaxMind GeoLite2/GeoIP2 database file (when MAXMIND_DATABASE_PATH is set)
 *   3. ipapi.co HTTP API (free tier — automatic fallback when above are not configured)
 *   4. Configured fallback (DEFAULT_COUNTRY_CODE / DEFAULT_CURRENCY)
 *
 * The result is used ONLY for suggesting a checkout currency to the client.
 * The authoritative price and currency are always read server-side from
 * prereg.amount and prereg.currency — never from client input.
 */
class GeoLocationService
{
    /**
     * Resolve the country code and currency for the given request.
     *
     * @param  Request $request
     * @return array{countryCode: string, currency: string, source: string}
     */
    public function resolve(Request $request): array
    {
        $ip = $request->getClientIps();
        dd($_SERVER['REMOTE_ADDR']);
        // --- LOCAL DEV ONLY: X-Geo-Test-IP override header ---
        // Allows testing real IP geo-lookup from localhost.
        // Completely ignored in production (APP_ENV != local).
        //if (app()->environment('local')) {
            //$testIp = $request->header('X-Geo-Test-IP');
            //if ($testIp && filter_var($testIp, FILTER_VALIDATE_IP)) {
               // Log::info('[GeoLocation] 🧪 DEBUG: X-Geo-Test-IP override active', [
                //    'original_ip' => $ip,
                //    'test_ip'     => $testIp,
                //]);
                //$ip = $testIp;
          //  }
       // }

        Log::info('[GeoLocation] Resolving country for IP:::', ['ip' => $ip]);

        // --- Priority 1: Cloudflare CF-IPCountry header ---
        if (config('geo.trust_cloudflare_headers', false)) {
            $cfCountry = $request->header('CF-IPCountry');
            if ($cfCountry && preg_match('/^[A-Z]{2}$/', $cfCountry)) {
                Log::info('[GeoLocation] ✅ PRIORITY 1 — Cloudflare CF-IPCountry header', [
                    'country' => $cfCountry,
                ]);
                return $this->buildResult($cfCountry, 'cloudflare');
            }
            Log::info('[GeoLocation] ⏭ Priority 1 skipped — CF-IPCountry header absent or invalid', [
                'cf_header' => $request->header('CF-IPCountry'),
            ]);
        } else {
            Log::info('[GeoLocation] ⏭ Priority 1 skipped — TRUST_CLOUDFLARE_HEADERS=false');
        }

        // --- Priority 2: MaxMind database (if configured) ---
        $dbPath = config('geo.maxmind_database_path', '');
        if (!empty($dbPath) && file_exists($dbPath)) {
            try {
                $reader  = new Reader($dbPath);
                $record  = $reader->country($ip);
                $country = $record->country->isoCode;
                if ($country && preg_match('/^[A-Z]{2}$/', $country)) {
                    Log::info('[GeoLocation] ✅ PRIORITY 2 — MaxMind database', [
                        'country' => $country,
                    ]);
                    return $this->buildResult($country, 'maxmind');
                }
            } catch (\Exception $e) {
                Log::debug('[GeoLocation] ⚠ Priority 2 MaxMind error', [
                    'error' => substr($e->getMessage(), 0, 200),
                ]);
            }
        } else {
            Log::info('[GeoLocation] ⏭ Priority 2 skipped — MaxMind DB not configured or file not found', [
                'maxmind_path' => $dbPath ?: '(not set)',
            ]);
        }

        // --- Priority 3: ipapi.co → ip-api.com (automatic failover) ---
        if (!$this->isPrivateIp($ip)) {

            // 3a: ipapi.co (with optional API key for higher limits)
            try {
                Log::info('[GeoLocation] 🌐 Priority 3a — calling ipapi.co', ['ip' => $ip]);

                $apiKey  = config('geo.ipapi_co_key', '');
                $url     = empty($apiKey)
                    ? "https://ipapi.co/{$ip}/json/"
                    : "https://ipapi.co/{$ip}/json/?key={$apiKey}";

                $response = Http::timeout(3)->get($url);

                if ($response->successful()) {
                    $data     = $response->json();
                    $country  = strtoupper($data['country_code'] ?? '');
                    $currency = strtoupper($data['currency'] ?? '');

                    Log::info('[GeoLocation] ✅ PRIORITY 3a — ipapi.co responded', [
                        'country_code' => $country,
                        'currency'     => $currency,
                    ]);

                    if (preg_match('/^[A-Z]{2}$/', $country)) {
                        if (!empty($currency) && preg_match('/^[A-Z]{3}$/', $currency)) {
                            return ['countryCode' => $country, 'currency' => $currency, 'source' => 'ipapi.co'];
                        }
                        return $this->buildResult($country, 'ipapi.co');
                    }
                } elseif ($response->status() === 429) {
                    Log::warning('[GeoLocation] ⚠ Priority 3a — ipapi.co rate limited (429), trying ip-api.com');
                } else {
                    Log::warning('[GeoLocation] ⚠ Priority 3a — ipapi.co non-2xx', ['status' => $response->status()]);
                }
            } catch (\Exception $e) {
                Log::warning('[GeoLocation] ⚠ Priority 3a — ipapi.co exception', [
                    'error' => substr($e->getMessage(), 0, 200),
                ]);
            }

            // 3b: ip-api.com — no key required, 45 req/min free tier
            try {
                Log::info('[GeoLocation] 🌐 Priority 3b — calling ip-api.com', ['ip' => $ip]);

                $response = Http::timeout(3)
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,countryCode,currency',
                    ]);

                if ($response->successful()) {
                    $data     = $response->json();
                    $country  = strtoupper($data['countryCode'] ?? '');
                    $currency = strtoupper($data['currency'] ?? '');

                    Log::info('[GeoLocation] ✅ PRIORITY 3b — ip-api.com responded', [
                        'country_code' => $country,
                        'currency'     => $currency,
                    ]);

                    if (preg_match('/^[A-Z]{2}$/', $country)) {
                        if (!empty($currency) && preg_match('/^[A-Z]{3}$/', $currency)) {
                            return ['countryCode' => $country, 'currency' => $currency, 'source' => 'ip-api.com'];
                        }
                        return $this->buildResult($country, 'ip-api.com');
                    }
                } else {
                    Log::warning('[GeoLocation] ⚠ Priority 3b — ip-api.com non-2xx', ['status' => $response->status()]);
                }
            } catch (\Exception $e) {
                Log::warning('[GeoLocation] ⚠ Priority 3b — ip-api.com exception', [
                    'error' => substr($e->getMessage(), 0, 200),
                ]);
            }

        } else {
            Log::info('[GeoLocation] ⏭ Priority 3 skipped — private/loopback IP', ['ip' => $ip]);
        }


        // --- Priority 4: Configured fallback ---
        $fallbackCountry = strtoupper(config('geo.default_country_code', 'US'));
        Log::info('[GeoLocation] ⏬ PRIORITY 4 — fallback', ['country' => $fallbackCountry]);
        return $this->buildResult($fallbackCountry, 'fallback');
    }

    /**
     * Map a country code to its currency and build the result array.
     *
     * @param  string $countryCode ISO 3166-1 alpha-2
     * @param  string $source      Resolution source for debugging
     * @return array{countryCode: string, currency: string, source: string}
     */
    private function buildResult(string $countryCode, string $source): array
    {
        $map      = config('geo.country_currency_map', []);
        $currency = $map[$countryCode] ?? config('geo.default_currency', 'USD');

        return [
            'countryCode' => $countryCode,
            'currency'    => $currency,
            'source'      => $source,
        ];
    }

    /**
     * Returns true if the IP is loopback or RFC-1918 private.
     * ipapi.co cannot geo-locate private IPs — skip the API call for them.
     */
    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
