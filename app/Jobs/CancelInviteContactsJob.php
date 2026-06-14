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

class CancelInviteContactsJob implements ShouldQueue
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
        Log::info("Running CancelInviteContactsJob on " . $this->inviteData->guest);

        // Get guest emails
        $guestEmails = explode(",", $this->inviteData->guest);
        
        foreach ($guestEmails as $guestEmail) {
            $guestEmail = trim($guestEmail);
            
            if (empty($guestEmail)) {
                continue;
            }

            // Check if the email has an account on Konn3ct
            $guestUser = User::where('email', $guestEmail)->select('id', 'email')->first();
            
            if ($guestUser) {
                // Find all received invites for this user that match the original invite
                InvitesModel::where('user_id', $guestUser->id)
                    ->where('title', $this->inviteData->title)
                    ->where('date', $this->inviteData->date)
                    ->where('time', $this->inviteData->time)
                    ->where('hostname', $this->inviteData->hostname)
                    ->delete();
                    
                Log::info("Deleted received invite for " . $guestEmail);
            }
        }
    }
}
