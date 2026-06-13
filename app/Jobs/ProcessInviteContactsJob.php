<?php

namespace App\Jobs;

use App\Models\InvitesModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessInviteContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public InvitesModel $inviteData;
    
    public function __construct(InvitesModel $inviteData)
    {
        $this->inviteData = $inviteData;
    }

    public function handle()
    {
        Log::info("Running ProcessInviteContactsJob on ".$this->inviteData->guest);

        // Handle InvitesModel
        $guestEmails = explode(",", $this->inviteData->guest);
        
        foreach ($guestEmails as $guestEmail) {
            $guestEmail = trim($guestEmail);
            
            if (empty($guestEmail)) {
                continue;
            }

            // Check if the email has an account on Konn3ct
            $guestUser = User::where('email', $guestEmail)->select('id','email')->first();
            
            if ($guestUser) {
                //Replicate data
                $gInv=$this->inviteData->replicate();
                $gInv->user_id = $guestUser->id;
                $gInv->save();
            }
        }
    }
}

