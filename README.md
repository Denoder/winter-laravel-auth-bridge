# winter-laravel-auth
This will allow you to use Laravel's core auth with WinterCMS

## How to install
`composer require winter-bridge/bridge`

`php artisan vendor:publish --tag=winter-bridge-config` - this will create an `auth.php` config file in the `congfig/` folder with settings for winter

----

In bootstrap/app.php change:
```
$app = new Winter\Storm\Foundation\Application(
    realpath(__DIR__.'/../')
);
```
to
```
$app = new Winter\Bridge\Foundation\Application(
    realpath(__DIR__.'/../')
);
```

and change:
```
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Winter\Storm\Foundation\Http\Kernel::class
);
```
to
```
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Winter\Bridge\Foundation\Http\Kernel::class
);
```

----

Next in `config/app.php` add in the `providers` **after** `System\SystemProvider`:
```
Illuminate\Auth\AuthServiceProvider::class,
Winter\Bridge\Auth\AuthServiceProvider::class,
```

now if you want to use said auth you'd do so in the user plugin:

```
Auth::extend('winter', function($app, $name, array $config)
{
    $guard = new \Winter\User\Classes\AuthManager;
    return $guard;
});
```

(you can also add the `Gate` alias if you want)

After that the package should essentially act as a layer over Laravel's auth while retaining winter's functions.
