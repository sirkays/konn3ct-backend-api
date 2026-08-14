<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Durable server-side mapping between BBB recordings and prereg events.
     *
     * Why this table:
     *   BBB recordings are identified by a `recordID` (e.g.
     *   "bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-1669190001680").
     *   The `meetingID` embedded in the recordID metadata maps to `room.id`.
     *   `room.prereg` stores the `prereg.reference` slug of the currently
     *   associated event.
     *
     * This table persists the resolved mapping at sync time so replay access
     * checks do not need to call the BBB API on every request, and so the
     * mapping survives a room being re-assigned to a different event later.
     *
     * Sync mechanism:
     *   `EventRecordingSyncService::sync(int $userId)` calls
     *   `Bigbluebutton::getRecordings(['meetingID' => $roomIds])`, resolves
     *   `room.prereg` → `prereg.reference` → `prereg.id`, and upserts rows.
     *
     * Security:
     *   The client NEVER supplies the event_id or room_id. Only the recordID
     *   is accepted from the client; all other fields are resolved server-side.
     *   Unknown or ambiguous recordIDs fail closed (404).
     */
    public function up()
    {
        Schema::create('event_recordings', function (Blueprint $table) {
            $table->id();

            // BBB recordID — globally unique on the BBB server.
            $table->string('recording_id', 300)->unique()->index();

            // BBB meetingID — matches room.id.
            $table->unsignedBigInteger('room_id')->index();

            // Resolved prereg.id — the event this recording belongs to.
            $table->unsignedBigInteger('event_id')->index();

            // BBB recording state at last sync: 'published', 'unpublished', 'deleted'.
            $table->string('state', 30)->default('published');

            // When this mapping was last synced from BBB.
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            // Composite index for the entitlement check.
            $table->index(['recording_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('event_recordings');
    }
}
