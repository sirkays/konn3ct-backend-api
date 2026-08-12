<?php

namespace App\Services\Odoo;

use App\Models\User;
use App\Models\RoomModel;

/**
 * OdooUsageMetricsProvider
 *
 * Provides verified usage metrics for a Konn3ct user.
 *
 * Amendment rules (Task 1 API-027 Amendment):
 *  - Include ONLY metrics with a verified, reliable data source.
 *  - Do NOT include null, invented zero, or placeholder for unavailable metrics.
 *  - A genuine zero is valid when the data source confirms the user's value is zero.
 *  - Do NOT create new tracking tables as part of this task.
 *  - At least one verified metric must be present to dispatch.
 *
 * Verified metrics in current codebase:
 *  - meetings_hosted: Count of rooms owned by this user (room.user_id).
 *                     This is a reasonable proxy — every registered user gets
 *                     a default room, and additional rooms can be created.
 *                     The count represents rooms hosted, not sessions started.
 *
 * Intentionally omitted (data source not yet available):
 *  - meetings_joined: The meetings table records participants by email, but
 *                     its semantics (log entry vs. actual join) are unverified.
 *  - watch_duration_seconds: No column exists anywhere in the schema.
 *  - ai_notes_used: No column exists anywhere in the schema.
 *
 * Future additions: implement getMetrics() extension without changing the
 * delivery job or dispatcher.
 */
class OdooUsageMetricsProvider
{
    /**
     * Return verified metrics for the given user.
     *
     * @param int $userId
     * @return array|null  Associative metric array, or null if no metrics available.
     *                     null means: do not dispatch a signal for this user.
     */
    public function getMetrics(int $userId): ?array
    {
        $metrics = [];

        // --- meetings_hosted ---
        // Source: room table, user_id column. Reliable FK relationship.
        // Counts all rooms owned by this user (including the default room).
        $meetingsHosted = RoomModel::where('user_id', $userId)->count();
        // A genuine zero is valid — user with only a default room still has 1,
        // but a freshly cleaned user may have 0.
        $metrics['meetings_hosted'] = (int) $meetingsHosted;

        // --- meetings_joined ---
        // Source: meetings table, email field — semantics unverified.
        // OMITTED until the data source is confirmed.

        // --- watch_duration_seconds ---
        // Source: none — column does not exist.
        // OMITTED.

        // --- ai_notes_used ---
        // Source: none — column does not exist.
        // OMITTED.

        // Return null if there are no verified metrics to send.
        if (empty($metrics)) {
            return null;
        }

        return $metrics;
    }
}
