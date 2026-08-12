<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use App\Services\Odoo\OdooUsageMetricsProvider;
use Illuminate\Console\Command;

/**
 * DispatchOdooUsageMetrics
 *
 * Dispatches API-027 USAGE_METRICS signals for all eligible Konn3ct users.
 *
 * This command:
 *  - Processes users in chunks to avoid loading all users into memory.
 *  - Uses a stable daily idempotency key: USAGE_METRICS:{user_id}:{UTC-date}
 *  - Skips users with no verified metrics.
 *  - Reports counts without printing personal data.
 *  - Does nothing when ODOO19_USAGE_METRICS_ENABLED is false.
 *  - Uses withoutOverlapping() when scheduled.
 *
 * Scheduled via Kernel.php. Do not use as a substitute for
 * a continuously supervised queue worker.
 *
 * Enable in production only after the Konn3ct metrics source is verified.
 */
class DispatchOdooUsageMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odoo:dispatch-usage-metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch USAGE_METRICS signals to Odoo 19 for all eligible users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        OdooUsageMetricsProvider $metricsProvider,
        OdooPayloadFactory $payloadFactory,
        OdooSignalDispatcher $dispatcher
    ): int {
        if (!config('odoo.usage_metrics_enabled', false)) {
            $this->line('[odoo:dispatch-usage-metrics] Disabled (ODOO19_USAGE_METRICS_ENABLED=false). Exiting.');
            return self::SUCCESS;
        }

        if (!config('odoo.enabled', false)) {
            $this->line('[odoo:dispatch-usage-metrics] Odoo integration is disabled. Exiting.');
            return self::SUCCESS;
        }

        $utcDate = now()->utc()->toDateString();  // e.g. "2026-08-12"

        $queued  = 0;
        $skipped = 0;
        $failed  = 0;

        $this->info('[odoo:dispatch-usage-metrics] Starting usage metrics dispatch for date: ' . $utcDate);

        // Process users in chunks of 100 to avoid memory exhaustion.
        User::select(['id'])
            ->chunkById(100, function ($users) use (
                $metricsProvider,
                $payloadFactory,
                $dispatcher,
                $utcDate,
                &$queued,
                &$skipped,
                &$failed
            ) {
                foreach ($users as $user) {
                    try {
                        // Get verified metrics for this user.
                        $metrics = $metricsProvider->getMetrics($user->id);

                        if ($metrics === null) {
                            // No verified metrics available — skip.
                            $skipped++;
                            continue;
                        }

                        // Build validated payload.
                        $payload = $payloadFactory->usageMetrics($user->id, $metrics);

                        if ($payload === null) {
                            // Payload validation determined no metrics are usable.
                            $skipped++;
                            continue;
                        }

                        // Stable daily idempotency key.
                        $idempotencyKey = "USAGE_METRICS:{$user->id}:{$utcDate}";

                        $signal = $dispatcher->dispatch(
                            'USAGE_METRICS',
                            'usage_metrics',
                            $idempotencyKey,
                            $payload
                        );

                        if ($signal !== null) {
                            $queued++;
                        } else {
                            // Null means already dispatched today (idempotency duplicate).
                            $skipped++;
                        }

                    } catch (\Exception $e) {
                        $failed++;
                        // Log only the error — no personal data.
                        \Illuminate\Support\Facades\Log::error(
                            'odoo:dispatch-usage-metrics: error processing user',
                            [
                                'user_id' => $user->id,
                                'error'   => substr($e->getMessage(), 0, 300),
                            ]
                        );
                    }
                }
            });

        $this->info(sprintf(
            '[odoo:dispatch-usage-metrics] Complete. Queued: %d | Skipped: %d | Errors: %d',
            $queued,
            $skipped,
            $failed
        ));

        return self::SUCCESS;
    }
}
