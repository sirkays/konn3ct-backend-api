<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * EventRecording
 *
 * Durable server-side mapping between a BBB recordID and a prereg event.
 *
 * Sync chain:
 *   BBB.recordID → BBB.meetingID == room.id → room.prereg == prereg.reference → prereg.id
 *
 * Population:
 *   EventRecordingSyncService::sync(int $userId) calls the BBB API,
 *   resolves the chain, and upserts rows here. This service is invoked:
 *   (a) when a user opens their recordings page
 *   (b) as a background job
 *
 * Security:
 *   The client NEVER supplies event_id or room_id. The client provides only
 *   the recordID (which they can observe in the BBB recording metadata they
 *   already have access to). All other fields are server-resolved.
 *
 * @property int    $id
 * @property string $recording_id  BBB recordID
 * @property int    $room_id       room.id
 * @property int    $event_id      prereg.id
 * @property string $state         'published'|'unpublished'|'deleted'
 * @property \Carbon\Carbon|null $synced_at
 */
class EventRecording extends Model
{
    use HasFactory;

    protected $table = 'event_recordings';

    protected $guarded = ['id'];

    protected $casts = [
        'synced_at'  => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---------------------------------------------------------------------------
    // Lookup helpers
    // ---------------------------------------------------------------------------

    /**
     * Find the event_id for a given recordID.
     * Returns null if the recording is not mapped or is deleted/unpublished.
     *
     * Fails closed: unknown recordID → null → caller returns 404.
     *
     * @param  string $recordingId
     * @return int|null
     */
    public static function resolveEventId(string $recordingId): ?int
    {
        $record = static::where('recording_id', $recordingId)
            ->where('state', 'published')
            ->first();

        return $record?->event_id;
    }

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function room()
    {
        return $this->belongsTo(RoomModel::class, 'room_id');
    }

    public function event()
    {
        return $this->belongsTo(PreRegModel::class, 'event_id');
    }
}
