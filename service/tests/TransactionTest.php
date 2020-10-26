<?php

namespace Tests;

use App\Models\Controller\Controller;
use App\Models\Controller\ControllerFactory;
use App\Models\Television\Television;
use App\Models\Television\TelevisionFactory;
use App\Models\Transaction\TransactionFactory;
use Codeception\Test\Unit;

class TransactionTest extends Unit
{

    public const CAN_BE_SOLD_STANDALONE = true;
    public const INFINITE_MAX_EXTRAS = -1;
    public const CANNOT_BE_SOLD_STANDALONE = false;

    public function testExpectedTotal()
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


    protected function setUp()
    : void
    {
        $this->transactionFactory = new TransactionFactory();
        $this->televisionFactory = new TelevisionFactory(self::INFINITE_MAX_EXTRAS, self::CAN_BE_SOLD_STANDALONE);
        $this->controllerFactory = new ControllerFactory(self::CANNOT_BE_SOLD_STANDALONE);
        parent::setUp();
    }

    private function getElectronicItemsForScenario1()
    : array
    {
        $television1Quantity = 2;
        $television1price = 500.5;
        $controller1price = 50;
        $controller1quantity = 3;
        $totalPriceForTelevision1 = ($television1price + ($controller1price * $controller1quantity)) * $television1Quantity;


        $television1 = $this->getTelevision(1, 'Standard television', $television1price);
        $television1->addExtra(
            $this->getController(1, 'standard controller', $controller1price, true),
            $controller1quantity
        );

        return [
            'example'        => [
                [
                    'item'     => $television1,
                    'quantity' => $television1Quantity
                ]
            ],
            'total'          => $totalPriceForTelevision1,
            'lineItemsCount' => 1
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

    private TransactionFactory $transactionFactory;
    private TelevisionFactory $televisionFactory;
    private ControllerFactory $controllerFactory;

}
