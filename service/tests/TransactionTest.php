<?php

namespace Tests;

use App\Models\Console\Console;
use App\Models\Console\ConsoleFactory;
use App\Models\Controller\Controller;
use App\Models\Controller\ControllerFactory;
use App\Models\Microwave\Microwave;
use App\Models\Microwave\MicrowaveFactory;
use App\Models\Television\Television;
use App\Models\Television\TelevisionFactory;
use app\Models\Transaction\LineItem;
use App\Models\Transaction\TransactionFactory;
use Codeception\Test\Unit;

class TransactionTest extends Unit
{

    public const CAN_BE_SOLD_STANDALONE = true;
    public const INFINITE_MAX_EXTRAS = -1;
    public const CANNOT_BE_SOLD_STANDALONE = false;
    public const FOUR_MAX_EXTRAS = 4;

    /**
     * @group total
     */
    public function testTransactionTotalIsAsExpected()
    {
        $testScenarioExample1 = $this->getElectronicItemsForScenario1();
        ['total' => $total, 'example' => $electronicItems] = $testScenarioExample1;

        $transaction = $this->transactionFactory->createTransaction($electronicItems);

        $this->assertEquals($total, $transaction->getTotalPrice());
    }


    public function testGetLineItems()
    {
        $testScenarioExample1 = $this->getElectronicItemsForScenario1();
        ['lineItemsCount' => $lineItemsCount, 'example' => $electronicItems] = $testScenarioExample1;

        $transaction = $this->transactionFactory->createTransaction($electronicItems);
        $this->assertCount($lineItemsCount, $transaction->getLineItems());
    }

    /**
     * @group sort
     */
    public function testItemsAreSortedAsExpected()
    {
        $testScenarioExample1 = $this->getElectronicItemsForScenario1();
        ['example' => $electronicItems] = $testScenarioExample1;

        $transaction = $this->transactionFactory->createTransaction($electronicItems);

        /**
         * @var LineItem[] $sortedLineItems
         */
        $ascSortedLineItems = array_values($transaction->getSortedLineItems(false));

        for ($i = 0; $i < count($ascSortedLineItems) - 1; $i++) {
            $this->assertLessThanOrEqual(
                $ascSortedLineItems[$i + 1]->getTotalPriceWithoutDecimals(),
                $ascSortedLineItems[$i]->getTotalPriceWithoutDecimals()
            );
        }

        /**
         * @var LineItem[] $sortedLineItems
         */
        $descSortedLineItems = array_values($transaction->getSortedLineItems(true));

        for ($i = 0; $i < count($descSortedLineItems) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $descSortedLineItems[$i + 1]->getTotalPriceWithoutDecimals(),
                $descSortedLineItems[$i]->getTotalPriceWithoutDecimals()
            );
        }
    }


    protected function setUp()
    : void
    {
        $this->transactionFactory = new TransactionFactory();
        $this->televisionFactory = new TelevisionFactory(self::INFINITE_MAX_EXTRAS, self::CAN_BE_SOLD_STANDALONE);
        $this->controllerFactory = new ControllerFactory(self::CANNOT_BE_SOLD_STANDALONE);
        $this->microwaveFactory = new MicrowaveFactory(self::CANNOT_BE_SOLD_STANDALONE);
        $this->consoleFactory = new ConsoleFactory(self::FOUR_MAX_EXTRAS, self::CANNOT_BE_SOLD_STANDALONE);
        parent::setUp();
    }

    private function getElectronicItemsForScenario1()
    : array
    {
        // Television 1 properties
        $television1Quantity = 1;
        $television1price = 1000.5;
        $controller1price = 100;
        $controller1quantity = 2;
        $totalPriceForTelevision1 = ($television1price + ($controller1price * $controller1quantity)) * $television1Quantity;

        // Television 2 properties
        $television2Quantity = 1;
        $television2price = 800.35;
        $controller2price = 100;
        $controller2quantity = 1;
        $totalPriceForTelevision2 = ($television2price + ($controller2price * $controller2quantity)) *
            $television2Quantity;

        // Console properties
        $consoleQuantity = 1;
        $consolePrice = 2999.99;
        $controller3Price = 150;
        $controller3Quantity = 2;
        $controller4Price = 100;
        $controller4Quantity = 2;
        $totalPriceForConsole = ($consolePrice + ($controller3Price * $controller3Quantity) + ($controller4Price
                    * $controller4Quantity)) * $consoleQuantity;

        // Microwave properties
        $microwaveQuantity = 1;
        $microwavePrice = 1100.5;
        $totalPriceForMicrowave = $microwavePrice * $microwaveQuantity;


        $totalTransactionTotal = $totalPriceForTelevision1 + $totalPriceForTelevision2 + $totalPriceForConsole +
            $totalPriceForMicrowave;


        $television1 = $this->getTelevision(1, 'Standard television', $television1price);
        $television1->addExtra(
            $this->getController(1, 'standard controller', $controller1price, true),
            $controller1quantity
        );

        $television2 = $this->getTelevision(2, 'TV 2', $television2price);
        $television2->addExtra(
            $this->getController(1, 'Controller', $controller2price, true),
            $controller2quantity
        );

        $microwave = $this->getMicrowave($microwavePrice);

        $console = $this->getConsole($consolePrice);
        $console->addExtra(
            $this->getController(1, 'Controller', $controller3Price, true),
            $controller3Quantity
        );
        $console->addExtra(
            $this->getController(2, 'Controller', $controller4Price, true),
            $controller4Quantity
        );


        return [
            'example'        => [
                [
                    'item'     => $television1,
                    'quantity' => $television1Quantity
                ],
                [
                    'item'     => $television2,
                    'quantity' => $television2Quantity
                ],
                [
                    'item'     => $microwave,
                    'quantity' => $microwaveQuantity
                ],
                [
                    'item'     => $console,
                    'quantity' => $consoleQuantity
                ]
            ],
            'total'          => $totalTransactionTotal,
            'lineItemsCount' => 4
        ];
    }

    /**
     * @param string $id
     * @param string $name
     * @param float $price
     * @return Television
     */
    private function getTelevision(string $id, string $name, float $price)
    : Television {
        return $this->televisionFactory->createItemFromData(
            ['id' => $id, 'name' => $name, 'price' => $price]
        );
    }

    /**
     * @param string $id
     * @param string $name
     * @param float $price
     * @param bool $isWireless
     * @return Controller
     */
    private function getController(string $id, string $name, float $price, bool $isWireless)
    : Controller {
        return $this->controllerFactory->createItemFromData(
            [
                'id'         => $id,
                'name'       => $name,
                'price'      => $price,
                'isWireless' => $isWireless
            ]
        );
    }

    /**
     * @param float $microwavePrice
     * @return Microwave
     */
    private function getMicrowave(float $microwavePrice)
    : Microwave {
        return $this->microwaveFactory->createItemFromData(
            [
                'id'    => 1,
                'name'  => 'Microwave',
                'price' =>
                    $microwavePrice
            ]
        );
    }

    /**
     * @param float $consolePrice
     * @return Console
     */
    private function getConsole(float $consolePrice)
    : Console {
        return $this->consoleFactory->createItemFromData(
            [
                'id'    => 1,
                'name'  => 'Console',
                'price' =>
                    $consolePrice
            ]
        );
    }

    private TransactionFactory $transactionFactory;
    private TelevisionFactory $televisionFactory;
    private ControllerFactory $controllerFactory;
    private MicrowaveFactory $microwaveFactory;
    private ConsoleFactory $consoleFactory;

}
