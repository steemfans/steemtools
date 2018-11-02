<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SteemConnect\Client\Client;
use LightRPC\Client as LightRPC;
use SteemConnect\Config\Config;

class SteemProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register SteemConnect.
        $this->registerSteemConnect();
        // register LightRPC.
        $this->registerLightRPC();
    }

    /**
     * Generate a SteemConnect Config instance.
     *
     * @return Config
     */
    protected function generateConfig() : Config
    {
        // get client id and secret from config.
        $clientId = config('steem.sc2.client_id');
        $clientSecret = config('steem.sc2.client_secret');

        // creates a new SteemConnect configuration instance.
        $config = new Config($clientId, $clientSecret);

        // get all scopes and explode into an array.
        $scopes = explode(',', config('steem.sc2.scopes'));
        // set the scopes on the configuration instance.
        $config->setScopes($scopes);

        // get the return URL from config.
        $returnUrl = config('steem.sc2.return_url');
        // set the return URL on the config instance.
        $config->setReturnUrl($returnUrl);

        // get the community name.
        $community = config('steem.app.community');

        // set the community if one was set.
        if ($community) {
            $config->setCommunity($community);
        }

        // build an application name / version string.
        $appName = config('steem.app.name')."/".config('steem.app.version');
        // set the application name on the config instance.
        $config->setApp($appName);

        // base url
        $config->setBaseUrl('https://steemconnect.com');

        // return the config.
        return $config;
    }

    /**
     * Register the SteemConnect client on the IoC.
     */
    protected function registerSteemConnect()
    {
        // register the SteemConnect 2 client under a string alias.
        $this->app->singleton('sc2.client', function () {
            // get the config instance.
            $config = $this->generateConfig();
            // create and return the SteemConnect client instance.
            return new Client($config);
        });

        // register the client class itself with the same instance
        $this->app->singleton(Client::class, function () {
            // return the previous instance from IoC.
            return $this->app->make('sc2.client');
        });
    }

    /**
     * Register the LightPRC client on the IoC.
     */
    protected function registerLightRPC()
    {
        // register the LightRPC client under a string alias.
        $this->app->singleton('light-rpc.client', function () {
            // get the API url.
            $apiUrl = config('steem.rpc.url');
            // create and return a LightRPC instance.
            return new LightRPC($apiUrl);
        });

        // register the class itself with the same instance.
        $this->app->singleton(LightRPC::class, function () {
            // return the previous instance from IoC.
            return $this->app->make('light-rpc.client');
        });
    }
}
