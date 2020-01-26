<?php

namespace October\Bridge\Auth;

use October\Bridge\Auth\AuthManager;
use October\Rain\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->extendAuthSession();
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->offerPublishing();
    }
    
    /**
     * Extend the authenticator service.
     *
     * @return void
     */    
    protected function extendAuthSession()
    {
        $this->app['auth']->extend('october', function($app, $name, array $config) {
            $guard = new AuthManager;
            return $guard;
        });         
    }
    
    /**
     * Setup the resource publishing groups for Auth.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__).'/../config/auth.php' => config_path('auth.php'),
            ], 'october-bridge-config');
        }
    }
}
