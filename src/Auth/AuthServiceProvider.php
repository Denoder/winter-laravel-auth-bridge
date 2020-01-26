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
        $this->app['auth']->extend('october', function($app, $name, array $config)
        {
            $provider = $this->app['auth']->createUserProvider($config['provider']);

            $guard = new AuthManager($provider, $app['session.store']);

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($this->app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($this->app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
            }

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
