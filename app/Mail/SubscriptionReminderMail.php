<?php

namespace App\Mail;

use App\Models\PlanModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $jobi;

    public function __construct($jobi)
    {
        $this->jobi = $jobi;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $plan = PlanModel::find($this->jobi['user']->plan);

        return $this->markdown('vendor.notifications.subreminder')
            ->subject('Your subscription plan is ending soon!')->with([
                'plan' => $plan,
            ]);

    }
}
