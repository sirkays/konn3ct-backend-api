<?php

namespace App\Jobs;

use App\Models\VisitLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IpVisitFinderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;

    public function __construct(VisitLog $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "-- Running IPcheck for " . $this->id->email;

        $loc2 = file_get_contents('http://ip-api.com/json/' . $this->id->ip_address);
        echo $loc2;
        $obj = json_decode($loc2);

        if ($obj->status == "success") {
            $this->id->status = "success";
            $this->id->city = $obj->city;
            $this->id->region = $obj->regionName;
            $this->id->country = $obj->country;
            $this->id->provider = $obj->isp;
            $this->id->countryCode = $obj->countryCode;
            $this->id->timezone = $obj->timezone;
            $this->id->response = $loc2;
            $this->id->save();
            return;
        }

    }
}
