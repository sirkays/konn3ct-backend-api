<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trust Cloudflare CF-IPCountry Header
    |--------------------------------------------------------------------------
    | When true, the GeoLocationService will read the CF-IPCountry header
    | from the request as the primary country source. Only enable this when
    | all traffic is proxied through Cloudflare and the header cannot be
    | spoofed by a direct client connection.
    */
    'trust_cloudflare_headers' => (bool) env('TRUST_CLOUDFLARE_HEADERS', false),

    /*
    |--------------------------------------------------------------------------
    | MaxMind GeoLite2 / GeoIP2 Database Path
    |--------------------------------------------------------------------------
    | Absolute path to the MaxMind .mmdb database file on the server.
    | Leave empty to skip MaxMind resolution.
    |
    | Download from: https://dev.maxmind.com/geoip/geolite2-free-geolocation-data
    | Set MAXMIND_DATABASE_PATH=/path/to/GeoLite2-Country.mmdb in .env
    */
    'maxmind_database_path' => env('MAXMIND_DATABASE_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | ipapi.co API Key (optional)
    |--------------------------------------------------------------------------
    | ipapi.co is used as an automatic fallback when Cloudflare headers are
    | absent and MaxMind is not configured. The free tier allows 1,000 requests
    | per day without a key. For higher limits, sign up at https://ipapi.co
    | and set IPAPI_CO_KEY=your_key in .env.
    */
    'ipapi_co_key' => env('IPAPI_CO_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Fallback Country and Currency
    |--------------------------------------------------------------------------
    | Used when no geolocation source resolves the country.
    */
    'default_country_code' => env('DEFAULT_COUNTRY_CODE', 'US'),
    'default_currency'     => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Country → Currency Map
    |--------------------------------------------------------------------------
    | ISO 3166-1 alpha-2 country code → ISO 4217 currency code.
    | Extend this as new markets are added.
    */
    'country_currency_map' => [
        'NG' => 'NGN',
        'US' => 'USD',
        'GB' => 'GBP',
        'CA' => 'CAD',
        'AU' => 'AUD',
        'EU' => 'EUR',
        'DE' => 'EUR',
        'FR' => 'EUR',
        'NL' => 'EUR',
        'ES' => 'EUR',
        'IT' => 'EUR',
        'ZA' => 'ZAR',
        'GH' => 'GHS',
        'KE' => 'KES',
        'RW' => 'RWF',
        'TZ' => 'TZS',
        'UG' => 'UGX',
    ],

];
