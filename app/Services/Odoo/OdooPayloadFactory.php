<?php

namespace App\Services\Odoo;

use App\Models\User;

/**
 * OdooPayloadFactory
 *
 * Builds and validates the exact JSON payload for each of the five Odoo signals.
 *
 * Rules:
 *  - Returns only the business fields defined in the agreed contract.
 *  - Never includes passwords, hashes, tokens, MFA data, phone numbers,
 *    raw gateway payloads, authorization codes, card data, or secrets.
 *  - Lead source values are normalized to a predefined set.
 *  - Currency and gateway names are normalized.
 *  - Monetary amounts are always in major currency units (not minor units).
 */
class OdooPayloadFactory
{
    // -------------------------------------------------------------------------
    // Normalized lead source values
    // -------------------------------------------------------------------------
    const LEAD_SOURCES = [
        'web',
        'mobile_app',
        'social_google',
        'social_apple',
        'social_facebook',
        'reseller',
        'country_landing_page',
        'paid_event_registration',
        'api',
    ];

    // -------------------------------------------------------------------------
    // API-026 — USER_REGISTERED
    // -------------------------------------------------------------------------

    /**
     * Build the USER_REGISTERED payload.
     *
     * @param int         $userId
     * @param string      $name          Normalized full name (firstname + lastname)
     * @param string      $email         Normalized persisted email
     * @param string|null $countryCode   Uppercase ISO country code (nullable — see notes)
     * @param string|null $referral      Acquisition/referrer code (users.referral)
     * @param string      $leadSource    Normalized lead source
     * @param string|null $ip            Trusted request IP (null for background jobs)
     *
     * @return array
     *
     * @note country_code: The current users table does not have a country_code column.
     *       This field is null until a GeoIP source or schema addition is provided.
     *
     * @note referral: This is the code the registrant entered (users.referral),
     *       NOT the user's own new code (users.referral_code).
     */
    public function userRegistered(
        int $userId,
        string $name,
        string $email,
        ?string $countryCode,
        ?string $referral,
        string $leadSource,
        ?string $ip
    ): array {
        $normalizedSource = in_array($leadSource, self::LEAD_SOURCES, true)
            ? $leadSource
            : 'api';

        $payload = [
            'user_id'       => $userId,
            'name'          => trim($name),
            'email'         => mb_strtolower(trim($email)),
            'country_code'  => $countryCode ? mb_strtoupper(trim($countryCode)) : null,
            'referral_code' => $referral ?: null,
            'lead_source'   => $normalizedSource,
            'ip'            => $ip ?: null,
        ];

        return $payload;
    }

    // -------------------------------------------------------------------------
    // API-027 — USAGE_METRICS
    // -------------------------------------------------------------------------

    /**
     * Build the USAGE_METRICS payload.
     *
     * Only include metrics provided by the caller. Never include null or
     * fabricated zero for unavailable metrics.
     *
     * @param int   $userId
     * @param array $metrics  Associative array of verified metric name => integer value.
     *                        Keys: meetings_hosted, meetings_joined,
     *                              watch_duration_seconds, ai_notes_used
     *
     * @return array|null  null if no verified metrics are available (do not dispatch)
     */
    public function usageMetrics(int $userId, array $metrics): ?array
    {
        $allowedKeys = ['meetings_hosted', 'meetings_joined', 'watch_duration_seconds', 'ai_notes_used'];

        $verified = [];
        foreach ($allowedKeys as $key) {
            if (isset($metrics[$key]) && is_int($metrics[$key]) && $metrics[$key] >= 0) {
                $verified[$key] = $metrics[$key];
            }
        }

        // Do not dispatch if there are no verified metrics beyond user_id.
        if (empty($verified)) {
            return null;
        }

        return array_merge(['user_id' => $userId], $verified);
    }

    // -------------------------------------------------------------------------
    // API-028 — PAYMENT_SUCCESS
    // -------------------------------------------------------------------------

    /**
     * Build the PAYMENT_SUCCESS payload.
     *
     * @param string $transactionReference  Stable reference from the payment gateway
     * @param int    $userId
     * @param float  $amount                Major-unit amount (e.g. 27000.00, NOT 2700000)
     * @param string $currency              ISO currency code (will be uppercased)
     * @param int    $planOrEventId         Persisted plan ID for subscription payments
     * @param string $gateway               Gateway name (will be lowercased)
     *
     * @return array
     */
    public function paymentSuccess(
        string $transactionReference,
        int $userId,
        float $amount,
        string $currency,
        int $planOrEventId,
        string $gateway
    ): array {
        return [
            'transaction_reference' => $transactionReference,
            'user_id'               => $userId,
            'amount'                => round($amount, 2),
            'currency'              => mb_strtoupper(trim($currency)),
            'plan_or_event_id'      => $planOrEventId,
            'gateway'               => mb_strtolower(trim($gateway)),
        ];
    }

    // -------------------------------------------------------------------------
    // API-028 — PAYMENT_FAILED
    // -------------------------------------------------------------------------

    /**
     * Build the PAYMENT_FAILED payload.
     *
     * @param string $transactionReference
     * @param int    $userId
     * @param float  $amount               Major-unit amount
     * @param string $gateway              Normalized gateway name
     * @param string $errorCode            Safe normalized error code (no raw gateway response)
     * @param bool   $abandonedCart        True only when confirmed abandoned checkout state
     *
     * @return array
     */
    public function paymentFailed(
        string $transactionReference,
        int $userId,
        float $amount,
        string $gateway,
        string $errorCode,
        bool $abandonedCart
    ): array {
        return [
            'transaction_reference' => $transactionReference,
            'user_id'               => $userId,
            'amount'                => round($amount, 2),
            'gateway'               => mb_strtolower(trim($gateway)),
            'error_code'            => $errorCode,
            'abandoned_cart'        => $abandonedCart,
        ];
    }

    // -------------------------------------------------------------------------
    // API-028 — PAID_EVENT_PURCHASE
    // -------------------------------------------------------------------------

    /**
     * Build the PAID_EVENT_PURCHASE payload.
     *
     * @param int    $userId         Verified Konn3ct users.id
     * @param int    $eventId        Persisted Konn3ct event ID (prereg.id)
     * @param float  $ticketPrice    Verified event price (major units)
     * @param string $paymentStatus  Normalized final status — always 'paid'
     *
     * @return array
     */
    public function paidEventPurchase(
        int $userId,
        int $eventId,
        float $ticketPrice,
        string $paymentStatus = 'paid'
    ): array {
        return [
            'user_id'        => $userId,
            'event_id'       => $eventId,
            'ticket_price'   => round($ticketPrice, 2),
            'payment_status' => 'paid',  // always the confirmed terminal state
        ];
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Normalize a gateway name for signal payloads.
     * Maps legacy display names to canonical lowercase identifiers.
     */
    public static function normalizeGateway(string $gateway): string
    {
        $map = [
            'Paystack'     => 'paystack',
            'Flutterwave'  => 'flutterwave',
            'Stripe'       => 'stripe',
            'MasterCard'   => 'mastercard',
            'Vulte'        => 'vulte',
        ];

        return $map[$gateway] ?? mb_strtolower(trim($gateway));
    }
}
