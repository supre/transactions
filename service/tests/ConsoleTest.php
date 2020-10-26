<?php

namespace Tests;


use App\Exceptions\MaxExtrasExceeded;
use App\Models\Console\ConsoleFactory;
use App\Models\Controller\ControllerFactory;
use Codeception\Specify;
use Codeception\Test\Unit;


class ConsoleTest extends Unit
{
    use Specify;

    public const MAX_EXTRAS = 4;
    public const CAN_BE_SOLD_STANDALONE = true;
    public const CANNOT_BE_SOLD_STANDALONE = false;
    public const IS_WIRELESS = true;

    public function testControllerQuantityExceedingMaxControllersThrowsExceptionCase1()
    : void
    {
        $this->specify(
            'Exceeding max extras will throw exception',
            function () {
                $this->expectException(MaxExtrasExceeded::class);

                $console = $this->consoleFactory->createItemFromData(
                    [
                        'id'    => 1,
                        'name'  => 'Console 1',
                        'price' => 1500
                    ]
                );

                $controller = $this->controllerFactory->createItemFromData(
                    [
                        'id'         => 1,
                        'name'       => "Basic Controller 1}",
                        'price'      => 10,
                        'isWireless' => self::IS_WIRELESS
                    ]
                );

                $quantity = self::MAX_EXTRAS + 1;
                $console->addExtra($controller, $quantity);
            }
        );
    }

    public function testControllerQuantityExceedingMaxControllersThrowsExceptionCase2()
    : void
    {
        $this->specify(
            'Exceeding max extras will throw exception',
            function () {
                $this->expectException(MaxExtrasExceeded::class);

                $console = $this->consoleFactory->createItemFromData(
                    [
                        'id'    => 1,
                        'name'  => 'Console 1',
                        'price' => 1500
                    ]
                );

                $controller1 = $this->controllerFactory->createItemFromData(
                    [
                        'id'         => 1,
                        'name'       => "Basic Controller 1",
                        'price'      => 10,
                        'isWireless' => self::IS_WIRELESS
                    ]
                );

                $controller2 = $this->controllerFactory->createItemFromData(
                    [
                        'id'         => 2,
                        'name'       => "Basic Controller 2",
                        'price'      => 30,
                        'isWireless' => self::IS_WIRELESS
                    ]
                );

                $quantity = 1;
                $console->addExtra($controller1, $quantity);
                $console->addExtra($controller2, self::MAX_EXTRAS);
            }
        );
    }

    protected function setUp()
    : void
    {
        parent::setUp();

        $this->consoleFactory = new ConsoleFactory(self::MAX_EXTRAS, self::CAN_BE_SOLD_STANDALONE);
        $this->controllerFactory = new ControllerFactory(self::CANNOT_BE_SOLD_STANDALONE);
    }

    protected function tearDown()
    : void
    {
        parent::tearDown();
        codecept_debug('teardown');
    }

    private array $extras = [];
    private ConsoleFactory $consoleFactory;
    private ControllerFactory $controllerFactory;
}
