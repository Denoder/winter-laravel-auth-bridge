<?php

namespace October\Bridge\Auth;

use October\Rain\Support\ServiceProvider;
use October\Bridge\Classes\AuthManager;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app['auth']->extend('session.extended', function($app, $name, array $config)
        {
            $provider = $this->app['auth']->createUserProvider($config['provider']);

            $guard = new AuthManager($name, $provider, $app['session.store']);

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
}
