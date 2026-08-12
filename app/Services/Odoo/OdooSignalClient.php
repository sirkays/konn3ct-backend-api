<?php

namespace App\Services\Odoo;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OdooSignalClient
 *
 * Handles outbound HTTP delivery to Odoo 19 inbound API endpoints.
 *
 * Security rules:
 *  - Always uses HTTPS in non-local/testing environments.
 *  - Never disables TLS certificate verification.
 *  - Never logs the raw request body, API token, Authorization header,
 *    signing secret, or full Odoo response body.
 *  - Sends credentials only in headers, never in query parameters.
 *  - HMAC signature is included in X-Konn3ct-Signature when a signing
 *    secret is configured.
 */
class OdooSignalClient
{
    /**
     * Deliver a signal to the configured Odoo endpoint.
     *
     * @param string $eventId       Stable UUID for this event (preserved across retries)
     * @param string $eventName     Signal name (e.g. 'USER_REGISTERED')
     * @param string $endpointKey   Key in config('odoo.endpoints')
     * @param string $idempotencyKey Stable business idempotency key
     * @param array  $payload       Decrypted business payload to send
     *
     * @return array  ['success' => bool, 'http_status' => int|null, 'error' => string|null, 'retryable' => bool]
     */
    public function send(
        string $eventId,
        string $eventName,
        string $endpointKey,
        string $idempotencyKey,
        array $payload
    ): array {
        $url = $this->resolveUrl($endpointKey);
        if ($url === null) {
            return [
                'success'     => false,
                'http_status' => null,
                'error'       => 'Endpoint not configured for: ' . $endpointKey,
                'retryable'   => false,
            ];
        }

        $this->enforceHttps($url);

        $timestamp  = now()->utc()->toIso8601String();
        $bodyJson   = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $headers    = $this->buildHeaders($eventId, $eventName, $idempotencyKey, $timestamp, $bodyJson);

        try {
            $response = Http::withHeaders($headers)
                ->timeout(config('odoo.request_timeout', 15))
                ->withBody($bodyJson, 'application/json')
                ->post($url);

            return $this->classifyResponse($response);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Network/connection error — retryable.
            Log::warning('OdooSignalClient: connection error', [
                'event_id'     => $eventId,
                'event_name'   => $eventName,
                'endpoint_key' => $endpointKey,
                'error'        => substr($e->getMessage(), 0, 200),
            ]);
            return [
                'success'     => false,
                'http_status' => null,
                'error'       => 'Connection error: ' . substr($e->getMessage(), 0, 200),
                'retryable'   => true,
            ];
        } catch (\Exception $e) {
            Log::error('OdooSignalClient: unexpected error', [
                'event_id'   => $eventId,
                'event_name' => $eventName,
                'error'      => substr($e->getMessage(), 0, 200),
            ]);
            return [
                'success'     => false,
                'http_status' => null,
                'error'       => 'Unexpected error: ' . substr($e->getMessage(), 0, 200),
                'retryable'   => true,
            ];
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Resolve the full delivery URL for a given endpoint key.
     *
     * @return string|null null if base_url or path is not configured.
     */
    private function resolveUrl(string $endpointKey): ?string
    {
        $baseUrl = rtrim(config('odoo.base_url', ''), '/');
        $path    = config("odoo.endpoints.{$endpointKey}", '');

        if (empty($baseUrl) || empty($path)) {
            return null;
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * Enforce HTTPS for non-local, non-testing environments.
     *
     * @throws \RuntimeException
     */
    private function enforceHttps(string $url): void
    {
        $env = config('app.env');
        if (in_array($env, ['local', 'testing'], true)) {
            return;
        }

        if (!str_starts_with(mb_strtolower($url), 'https://')) {
            throw new \RuntimeException(
                'OdooSignalClient: Odoo endpoint must use HTTPS in ' . $env . ' environment.'
            );
        }
    }

    /**
     * Build the outbound HTTP headers.
     *
     * Includes HMAC signature only when a signing secret is configured.
     */
    private function buildHeaders(
        string $eventId,
        string $eventName,
        string $idempotencyKey,
        string $timestamp,
        string $bodyJson
    ): array {
        $token = config('odoo.api_token', '');

        $headers = [
            'Content-Type'               => 'application/json',
            'Accept'                     => 'application/json',
            'Authorization'              => 'Bearer ' . $token,
            'Idempotency-Key'            => $idempotencyKey,
            'X-Konn3ct-Event-Id'         => $eventId,
            'X-Konn3ct-Event-Name'       => $eventName,
            'X-Konn3ct-Schema-Version'   => config('odoo.schema_version', '1.0'),
            'X-Konn3ct-Timestamp'        => $timestamp,
        ];

        $signingSecret = config('odoo.signing_secret', '');
        if (!empty($signingSecret)) {
            // Sign the exact raw JSON bytes using HMAC-SHA256.
            // The signature is over the same bytes that will be sent as the body.
            $hmac = hash_hmac('sha256', $bodyJson, $signingSecret);
            $headers['X-Konn3ct-Signature'] = 'sha256=' . $hmac;
        }

        return $headers;
    }

    /**
     * Classify the HTTP response into a structured result.
     *
     * HTTP retry rules:
     *  - 2xx: delivered
     *  - 408, 425, 429, 5xx: retryable
     *  - 400, 422: contract/payload failure — blocked (no infinite retry)
     *  - 401, 403: configuration/security failure — log critical, retryable
     *    (queue retries will eventually exhaust, which is intentional)
     *  - Other 4xx: non-retryable
     */
    private function classifyResponse(Response $response): array
    {
        $status = $response->status();

        // Never log the raw response body — it may contain sensitive data.
        $sanitizedError = 'HTTP ' . $status;

        if ($response->successful()) {
            // HTTP 2xx
            return [
                'success'     => true,
                'http_status' => $status,
                'error'       => null,
                'retryable'   => false,
            ];
        }

        if (in_array($status, [408, 425, 429], true) || $status >= 500) {
            // Retryable: timeout, too early, rate limit, server error.
            return [
                'success'     => false,
                'http_status' => $status,
                'error'       => $sanitizedError,
                'retryable'   => true,
            ];
        }

        if (in_array($status, [401, 403], true)) {
            // Security/config failure. Log critically but allow queue retries.
            Log::critical('OdooSignalClient: authentication/authorization failure from Odoo', [
                'http_status' => $status,
            ]);
            return [
                'success'     => false,
                'http_status' => $status,
                'error'       => $sanitizedError . ' — check api_token and endpoint config',
                'retryable'   => true,
            ];
        }

        if (in_array($status, [400, 422], true)) {
            // Contract/payload failure — do not retry indefinitely.
            return [
                'success'     => false,
                'http_status' => $status,
                'error'       => $sanitizedError . ' — payload validation failure',
                'retryable'   => false,
            ];
        }

        // Other 4xx — non-retryable.
        return [
            'success'     => false,
            'http_status' => $status,
            'error'       => $sanitizedError,
            'retryable'   => false,
        ];
    }
}
