<?php

namespace App\Services;

use App\Models\EventRecording;
use App\Models\RoomModel;
use App\Models\PreRegModel;
use Illuminate\Support\Facades\Log;

/**
 * EventRecordingSyncService
 *
 * Syncs BBB recording metadata into the event_recordings table, establishing
 * the durable server-side mapping: recordID → room.id → prereg.id
 *
 * This mapping is used by ReplayAccessService to resolve the event from a
 * recordID without trusting client input.
 *
 * Sync chain:
 *   BBB.getRecordings(['meetingID' => $roomIds])
 *   → recording.meetingID == room.id
 *   → room.prereg == prereg.reference
 *   → prereg.id (event_id)
 */
class EventRecordingSyncService
{
    /**
     * Sync all recordings for the given user's rooms.
     *
     * @param  int $userId
     * @return int Number of recordings synced (upserted).
     */
    public function syncForUser(int $userId): int
    {
        $rooms = RoomModel::where('user_id', $userId)
            ->whereNotNull('prereg')
            ->where('prereg', '!=', '')
            ->get();

        if ($rooms->isEmpty()) {
            return 0;
        }

        $roomIdMap   = $rooms->pluck('prereg', 'id'); // [room.id => prereg.reference]
        $meetingIds  = $rooms->pluck('id')->toArray();
        $synced      = 0;

        try {
            if (app()->environment(['local', 'testing'])) {
                // In testing/local, skip the actual BBB API call.
                return 0;
            }

            $recordings = \Bigbluebutton::getRecordings(['meetingID' => $meetingIds]);

            if (!is_array($recordings)) {
                return 0;
            }

            // Normalize: getRecordings may return a JSON string or decoded array.
            if (is_string($recordings)) {
                $recordings = json_decode($recordings, true) ?? [];
            }

            foreach ($recordings as $rec) {
                $recordId  = $rec['recordID']  ?? null;
                $meetingId = $rec['meetingID'] ?? null;
                $state     = $rec['state']     ?? 'published';

                if (!$recordId || !$meetingId) {
                    continue;
                }

                $meetingIdInt = (int) $meetingId;
                $preregRef    = $roomIdMap[$meetingIdInt] ?? null;

                if (!$preregRef) {
                    continue; // This room has no prereg event — skip.
                }

                $prereg = PreRegModel::where('reference', $preregRef)->first();
                if (!$prereg) {
                    continue; // Orphaned prereg reference — skip.
                }

                EventRecording::updateOrCreate(
                    ['recording_id' => $recordId],
                    [
                        'room_id'   => $meetingIdInt,
                        'event_id'  => $prereg->id,
                        'state'     => $state,
                        'synced_at' => now(),
                    ]
                );

                $synced++;
            }
        } catch (\Exception $e) {
            Log::error('EventRecordingSyncService: BBB sync failed', [
                'user_id' => $userId,
                'error'   => substr($e->getMessage(), 0, 300),
            ]);
        }

        return $synced;
    }
}
