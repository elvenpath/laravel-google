<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Service_YouTube;
use Google_Service_YouTube_ResourceId;
class GoogleServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind("Google_Service_Youtube", function ($app, array $client) {
            return new Google_Service_Youtube($client[0]);
        });

        $this->app->bind("Google_Service_YouTube_ResourceId", function ($app, array $client) {
            return new Google_Service_YouTube_ResourceId();
        });


    }

}
