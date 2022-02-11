<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionReminderMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SubscriptionReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'samji:subscriptionreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where([['type', '!=', 'admin'], ['subscription', '!=', 'new'], ['status', '=', 'active'], ['plan', '!=', '1']])->get();

        foreach ($users as $user) {
            //getting 14 days reminder subscription
            if (Carbon::now()->diffInDays(Carbon::parse($user->subscription), false) == 13) {
                print($user->email);
                print("-");
                print(Carbon::now()->diffInDays(Carbon::parse($user->subscription), false));
                $jobi['days'] = Carbon::now()->diffInDays(Carbon::parse($user->subscription), false);
                $jobi['user'] = $user;

                try {
                    Mail::to($user->email)->queue(new SubscriptionReminderMail($jobi));
                } catch (\Exception $e) {
                    echo $e;
                }
            }
        }

        return 0;
    }
}
