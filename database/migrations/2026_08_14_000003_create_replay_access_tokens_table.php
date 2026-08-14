<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplayAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Short-lived, cryptographically opaque tokens for replay access.
     *
     * Security model:
     *  - Only the SHA-256 hash of the token is stored — never the plaintext.
     *  - Tokens are scoped to (user_id, recording_id) — cross-user and
     *    cross-recording tokens are always rejected.
     *  - Expired tokens are rejected.
     *  - Revoked tokens are rejected.
     *  - IMPORTANT: These tokens control access to the Konn3ct API only.
     *    The underlying BigBlueButton playback URL is permanently public on the
     *    BBB server. This is API-layer "sharing resistance", not full DRM.
     *    BBB protection is PARTIAL.
     */
    public function up()
    {
        Schema::create('replay_access_tokens', function (Blueprint $table) {
            $table->id();

            // SHA-256 hash of the plaintext token. Unique per token.
            $table->string('token_hash', 64)->unique();

            // The user this token was issued for.
            $table->unsignedBigInteger('user_id')->index();

            // The BBB recordID this token grants access to.
            $table->string('recording_id', 300)->index();

            // The prereg event this recording belongs to.
            // Resolved server-side — never accepted from the client.
            $table->unsignedBigInteger('event_id')->index();

            // When the token expires. Rejection is hard: no grace period.
            $table->timestamp('expires_at')->index();

            // When the token was revoked (nullable = not revoked).
            // Revocation is permanent and immediate.
            $table->timestamp('revoked_at')->nullable()->index();

            $table->timestamps();

            // Composite index for validation lookup.
            $table->index(['user_id', 'recording_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('replay_access_tokens');
    }
}
