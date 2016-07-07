<?php

namespace Hoanghiep\Googleapi;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Oauth2_Resource_Userinfo;

class GoogleClientProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() { 
        
        $this->publishes([
            __DIR__ . '/config/google.php' => config_path("/google.php"),
        ]);

        $this->publishes([
            __DIR__ . '/database/migrations/2016_07_03_010808_create_googles_table.php' => base_path() . ("/database/migrations/2016_07_03_010808_create_googles_table.php"),
        ]);


        $this->publishes([
            __DIR__ . '/model/Google.php' => app_path() . ("/Google.php"),
        ]);

       

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

    
        $this->publishes([
            __DIR__ . '/GoogleServiceProvider.php' => app_path("/Providers/GoogleServiceProvider.php"),
        ]);


        $this->publishes([
            __DIR__ . '/Hoanghiep/UserinfoController.php' => app_path("/Http/Controllers/Hoanghiep/UserinfoController.php"),
        ]);

        $this->publishes([
            __DIR__ . '/Hoanghiep/YoutubeController.php' => app_path("/Http/Controllers/Hoanghiep/YoutubeController.php"),
        ]);
        $this->publishes([
            __DIR__ . '/views/hoanghiep/auth.blade.php' => resource_path("views/hoanghiep/google/auth.blade.php"),
        ]);

        $this->loadViewsFrom(__DIR__.'/views', 'google');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind("Google_Client", function($app) {
            $client = new Google_Client();
            $client->setApplicationName(config("google.AppName"));
            $client->setDeveloperKey(config("google.AppKey"));
            $client->setClientId(config("google.client_id"));
            $client->setClientSecret(config("google.client_secret"));
            $client->setRedirectUri(config("google.redirect"));
            $client->setScopes(config("google.scopes"));
            $client->setAccessType("offline");
            $client->setIncludeGrantedScopes(true);
            return $client;
        });
        
           $this->app->bind("Google_Service_Oauth2", function ($app, array $client) {
            return new Google_Service_Oauth2($client[0]);
        });

        $this->app->bind("Google_Service_Oauth2_Resource_Userinfo", function ($app, array $client) {
            return new Google_Service_Oauth2_Resource_Userinfo($client[0]);
        });
    }

  
}
