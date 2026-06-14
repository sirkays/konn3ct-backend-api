<?php

namespace App\Jobs;

use App\Models\InvitesModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateInviteContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public InvitesModel $inviteData;
    public int $originalCreatorId;

    public function __construct(InvitesModel $inviteData, int $originalCreatorId)
    {
        $this->inviteData = $inviteData;
        $this->originalCreatorId = $originalCreatorId;
    }

    public function handle()
    {
        Log::info("Running UpdateInviteContactsJob on " . $this->inviteData->guest);

        // Get all guest emails
        $guestEmails = explode(",", $this->inviteData->guest);
        $guestUserIds = [];
        
        foreach ($guestEmails as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $guestUserIds[] = $user->id;
                }
            }
        }
        
        // Get existing received invites for these guest users
        $existingReceivedInvites = InvitesModel::whereIn('user_id', $guestUserIds)->get();

        // First, delete any received invites for guests no longer on the list
        foreach ($existingReceivedInvites as $receivedInvite) {
            $guestUser = User::find($receivedInvite->user_id);
            if ($guestUser) {
                $isStillGuest = in_array(trim($guestUser->email), array_map('trim', $guestEmails));
                if (!$isStillGuest) {
                    $receivedInvite->delete();
                }
            }
        }

        // Now update or create received invites for current guests
        foreach ($guestEmails as $guestEmail) {
            $guestEmail = trim($guestEmail);
            
            if (empty($guestEmail)) {
                continue;
            }

            // Check if the email has an account on Konn3ct
            $guestUser = User::where('email', $guestEmail)->select('id', 'email')->first();
            
            if ($guestUser) {
                // Find existing received invite for this guest
                $existingReceived = InvitesModel::where('user_id', $guestUser->id)
                    ->where('created_at', '<=', $this->inviteData->updated_at)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($existingReceived) {
                    // Update existing received invite
                    $existingReceived->update($this->inviteData->toArray());
                    Log::info("Updated received invite for " . $guestEmail);
                } else {
                    // Create new received invite
                    $gInv = $this->inviteData->replicate();
                    $gInv->user_id = $guestUser->id;
                    $gInv->save();
                    Log::info("Created new received invite for " . $guestEmail);
                }
            }
        }
    }
}
