<?php

namespace App\Providers;

use App\Http\Controllers\PortalController;
use App\Http\Controllers\TransactionController;
use App\Models\Console\ConsoleFactory;
use App\Models\Controller\ControllerFactory;
use App\Models\ElectronicItem;
use App\Models\Microwave\MicrowaveFactory;
use App\Models\Television\TelevisionFactory;
use App\Models\Transaction\TransactionFactory;
use App\Repositories\ElectronicItemsRepositoryFactory;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app instanceof Application) {
            $this->app->configure('electronicitems');

            $this->app->bind(
                PortalController::class,
                function (Application $app) {
                    $electronicItems = $this->app->make('allElectronicItems');
                    $appUrl = config('app.url');

                    return new PortalController($appUrl, $electronicItems);
                }
            );

            $this->app->bind(
                TransactionController::class,
                function (Application $app) {
                    $repositoryOfAllElectronicItems = $this->app->make('repositoryOfAllElectronicItems');
                    $transactionFactory = $this->app->make(TransactionFactory::class);
                    return new TransactionController($repositoryOfAllElectronicItems, $transactionFactory);
                }
            );

            $this->app->bind(
                'electricItemFactories',
                function (Application $app) {
                    return [
                        ElectronicItem::ELECTRONIC_ITEM_CONSOLE    => $app->make(ConsoleFactory::class),
                        ElectronicItem::ELECTRONIC_ITEM_CONTROLLER => $app->make(ControllerFactory::class),
                        ElectronicItem::ELECTRONIC_ITEM_MICROWAVE  => $app->make(MicrowaveFactory::class),
                        ElectronicItem::ELECTRONIC_ITEM_TELEVISION => $app->make(TelevisionFactory::class),
                    ];
                }
            );

            $this->app->bind(
                'allElectronicItems',
                function (Application $app) {
                    $electronicItems = [];
                    $electronicItemsFromConfig = config('electronicitems');
                    $factories = $this->app->make('electricItemFactories');

                    foreach ($electronicItemsFromConfig as $electronicItemType => $electronicItem) {
                        $factory = $factories[$electronicItemType];

                        foreach ($electronicItem['items'] as $item) {
                            $electronicItems[] = $factory->createItemFromData($item);
                        }
                    }

                    return $electronicItems;
                }
            );

            $this->app->bind(
                'repositoryOfAllElectronicItems',
                function (Application $app) {
                    $repositoryFactory = $this->app->make(ElectronicItemsRepositoryFactory::class);
                    $allElectronicItems = $this->app->make('allElectronicItems');
                    return $repositoryFactory->createRepository($allElectronicItems);
                }
            );

            $this->app->bind(
                ConsoleFactory::class,
                function (Application $app) {
                    $consoleConfig = config('electronicitems.' . ElectronicItem::ELECTRONIC_ITEM_CONSOLE);
                    return new ConsoleFactory($consoleConfig[MAX_EXTRAS], $consoleConfig[CAN_BE_SOLD_STANDALONE]);
                }
            );

            $this->app->bind(
                ControllerFactory::class,
                function (Application $app) {
                    $controllerConfig = config('electronicitems.' . ElectronicItem::ELECTRONIC_ITEM_CONTROLLER);
                    return new ControllerFactory($controllerConfig[CAN_BE_SOLD_STANDALONE]);
                }
            );

            $this->app->bind(
                MicrowaveFactory::class,
                function (Application $app) {
                    $controllerConfig = config('electronicitems.' . ElectronicItem::ELECTRONIC_ITEM_MICROWAVE);
                    return new MicrowaveFactory($controllerConfig[CAN_BE_SOLD_STANDALONE]);
                }
            );

            $this->app->bind(
                TelevisionFactory::class,
                function (Application $app) {
                    $config = config('electronicitems.' . ElectronicItem::ELECTRONIC_ITEM_TELEVISION);
                    return new TelevisionFactory($config[MAX_EXTRAS], $config[CAN_BE_SOLD_STANDALONE]);
                }
            );
        }
    }
}
