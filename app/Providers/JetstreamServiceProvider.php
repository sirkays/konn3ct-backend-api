<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\SettingsModel;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::registerView(function () {
            session(['plan' => '1']);
            $set=SettingsModel::first();
            $freetrial=$set->freetrial_status;
            $freetrial_days=$set->freetrial_days;
            return view('auth.register', ['freetrial'=>$freetrial, 'freetrial_days'=>$freetrial_days]);
        });

        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
