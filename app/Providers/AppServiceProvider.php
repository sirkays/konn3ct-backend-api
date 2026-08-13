<?php

namespace App\Providers;

use App\Contracts\Admin\MeetingEnforcementGateway;
use App\Services\Admin\UnsupportedMeetingEnforcementGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind MeetingEnforcementGateway to the unsupported implementation until a
        // confirmed force-disconnect/revocation endpoint is available on the
        // external Konn3ct meeting service (KONN3CT_BASE_URL).
        $this->app->bind(
            MeetingEnforcementGateway::class,
            UnsupportedMeetingEnforcementGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
