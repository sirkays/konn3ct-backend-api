<?php

namespace Tests\Feature\Security;

use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * VulteWebhookSecurityTest
 *
 * Proves that the Vulte webhook cannot set prereg_users.paid=1 from a
 * non-allowlisted IP address.
 *
 * Security limitation: Vulte has no published HMAC/signature contract.
 * IP allowlist is the interim mitigation. These tests verify that mitigation.
 */
class VulteWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Forged Vulte event webhook from a non-allowlisted IP is rejected.
     */
    public function test_vulte_event_hook_from_non_allowlisted_ip_is_rejected()
    {
        Config::set('vulte.webhook_ip_allowlist', ['1.2.3.4']); // Allowlist a different IP

        $event = PreRegModel::create([
            'user_id' => 1, 'room_id' => 1, 'title' => 'Test',
            'reference' => 'WEB-TEST', 'host_name' => 'Host',
            'date' => '2026-09-01', 'time' => '10:00', 'timezone' => 'UTC', 'about' => 'Test',
            'free' => 0, 'amount' => '100000', 'currency' => 'NGN',
        ]);

        $preregUser = PreRegUserModel::create([
            'prereg_id' => $event->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+234800000000',
            'paid' => 0,
        ]);

        $payload = [
            'event'   => 'transaction.charge',
            'data'    => [
                'status'   => 'successful',
                'pay_type' => 'event',
                'email'    => $preregUser->email,
                'amount'   => 100000,
            ],
        ];

        // Request comes from a non-allowlisted IP (127.0.0.1)
        $response = $this->postJson('/api/hook/vulte', $payload);

        $response->assertStatus(403);

        // Verify paid was NOT set.
        $this->assertDatabaseHas('prereg_users', [
            'id'   => $preregUser->id,
            'paid' => 0,
        ]);
    }

    /**
     * An empty allowlist rejects all Vulte webhooks (safe default).
     */
    public function test_empty_allowlist_rejects_all_vulte_webhooks()
    {
        Config::set('vulte.webhook_ip_allowlist', []);

        $response = $this->postJson('/api/hook/vulte', ['event' => 'transaction.charge']);

        $response->assertStatus(403);
    }

    /**
     * A wildcard allowlist (*) allows testing bypass.
     * This test confirms the bypass works only when explicitly configured.
     */
    public function test_wildcard_allowlist_bypasses_ip_check()
    {
        Config::set('vulte.webhook_ip_allowlist', ['*']);

        // Just verify the request gets past the middleware (may return other errors after)
        // The important thing is it doesn't return 403 from our middleware.
        $response = $this->postJson('/api/hook/vulte', []);

        $this->assertNotEquals(403, $response->getStatusCode());
    }

    /**
     * Forged Vulte bank transfer webhook from a non-allowlisted IP is rejected.
     */
    public function test_vulte_polaris_hook_from_non_allowlisted_ip_is_rejected()
    {
        Config::set('vulte.webhook_ip_allowlist', ['1.2.3.4']);

        $response = $this->postJson('/api/hook/polaris', [
            'status' => 'successful',
            'amount' => 100000,
        ]);

        $response->assertStatus(403);
    }
}
