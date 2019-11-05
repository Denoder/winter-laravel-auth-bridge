# october-laravel-auth
This will allow you to use Laravel's core auth with OctoberCMS

## How to install
`composer require october-bridge/bridge`

`php artisan vendor:publish --tag=october-bridge-config` - this will create an `auth.php` config file in the `congfig/` folder with settings for october

----

In bootstrap/app.php change:
```
$app = new October\Rain\Foundation\Application(
    realpath(__DIR__.'/../')
);
```
to
```
$app = new October\Bridge\Foundation\Application(
    realpath(__DIR__.'/../')
);
```

and change:
```
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    October\Rain\Foundation\Http\Kernel::class
);
```
to
```
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    October\Bridge\Foundation\Http\Kernel::class
);
```

----

Next in `config/app.php` add in the `providers` **after** `System\SystemProvider`:
```
Illuminate\Auth\AuthServiceProvider::class,
October\Bridge\Auth\AuthServiceProvider::class,
```

now if you want to use said auth you'd do so in the user plugin:

```
Auth::extend('october', function($app, $name, array $config)
{
    $guard = new \Rainlab\User\Classes\AuthManager;
    return $guard;
});
```

(you can also add the `Gate` alias if you want)

After that the package should essentially act as a layer over Laravel's auth while retaining october's functions.
