<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\TransactionController;
use App\Providers\AppServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->configure('app');

$app->register(AppServiceProvider::class);

$app->router->group(
    [],
    function ($router) {
        $router->app->make(PortalController::class)($router);
        $router->app->make(TransactionController::class)($router);
    }
);

return $app;
