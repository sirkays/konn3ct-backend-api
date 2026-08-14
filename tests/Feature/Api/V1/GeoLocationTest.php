<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * GeoLocationTest
 *
 * Tests the geo detection endpoint for country and currency resolution.
 */
class GeoLocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Default fallback returns configured defaults.
     */
    public function test_fallback_returns_default_country_and_currency()
    {
        Config::set('geo.trust_cloudflare_headers', false);
        Config::set('geo.maxmind_database_path', '');
        Config::set('geo.default_country_code', 'US');
        Config::set('geo.default_currency', 'USD');

        $response = $this->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJsonStructure(['country_code', 'currency'])
            ->assertJson(['country_code' => 'US', 'currency' => 'USD']);
    }

    /**
     * Cloudflare CF-IPCountry header is trusted when enabled.
     */
    public function test_cloudflare_header_is_used_when_enabled()
    {
        Config::set('geo.trust_cloudflare_headers', true);
        Config::set('geo.maxmind_database_path', '');
        Config::set('geo.country_currency_map', ['NG' => 'NGN']);

        $response = $this->withHeaders(['CF-IPCountry' => 'NG'])
            ->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJson(['country_code' => 'NG', 'currency' => 'NGN']);
    }

    /**
     * Cloudflare header is ignored when trust is disabled.
     */
    public function test_cloudflare_header_is_ignored_when_trust_disabled()
    {
        Config::set('geo.trust_cloudflare_headers', false);
        Config::set('geo.default_country_code', 'US');
        Config::set('geo.default_currency', 'USD');

        $response = $this->withHeaders(['CF-IPCountry' => 'NG'])
            ->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJson(['country_code' => 'US', 'currency' => 'USD']);
    }

    /**
     * Invalid CF-IPCountry header (not 2 uppercase letters) is ignored.
     */
    public function test_invalid_cloudflare_header_format_is_ignored()
    {
        Config::set('geo.trust_cloudflare_headers', true);
        Config::set('geo.default_country_code', 'US');
        Config::set('geo.default_currency', 'USD');

        $response = $this->withHeaders(['CF-IPCountry' => 'INVALID'])
            ->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJson(['country_code' => 'US', 'currency' => 'USD']);
    }

    /**
     * Country without explicit currency mapping uses default currency.
     */
    public function test_country_without_currency_mapping_uses_default()
    {
        Config::set('geo.trust_cloudflare_headers', true);
        Config::set('geo.country_currency_map', ['US' => 'USD']); // ZZ not in map
        Config::set('geo.default_currency', 'USD');

        $response = $this->withHeaders(['CF-IPCountry' => 'ZZ'])
            ->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJsonPath('currency', 'USD');
    }

    /**
     * Response has Cache-Control: no-store header.
     */
    public function test_response_has_no_cache_header()
    {
        $response = $this->getJson('/api/v1/geo/detect');

        // Laravel may append ', private' to no-store depending on middleware.
        $cacheControl = $response->headers->get('Cache-Control', '');
        $this->assertStringContainsString('no-store', $cacheControl,
            'Cache-Control header should contain no-store directive.');
    }

    /**
     * Endpoint is publicly accessible without authentication.
     */
    public function test_endpoint_is_public()
    {
        $response = $this->getJson('/api/v1/geo/detect');

        $response->assertStatus(200);
    }

    /**
     * Nigerian country code maps to NGN currency.
     */
    public function test_nigeria_maps_to_ngn()
    {
        Config::set('geo.trust_cloudflare_headers', true);
        Config::set('geo.country_currency_map', ['NG' => 'NGN', 'US' => 'USD']);

        $response = $this->withHeaders(['CF-IPCountry' => 'NG'])
            ->getJson('/api/v1/geo/detect');

        $response->assertJson(['currency' => 'NGN']);
    }

    /**
     * Maxmind path configured but file doesn't exist — falls back gracefully.
     */
    public function test_missing_maxmind_file_falls_back_to_default()
    {
        Config::set('geo.trust_cloudflare_headers', false);
        Config::set('geo.maxmind_database_path', '/nonexistent/path/GeoLite2-Country.mmdb');
        Config::set('geo.default_country_code', 'US');
        Config::set('geo.default_currency', 'USD');

        $response = $this->getJson('/api/v1/geo/detect');

        $response->assertStatus(200)
            ->assertJson(['country_code' => 'US', 'currency' => 'USD']);
    }
}
