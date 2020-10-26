<?php

namespace App\Tests;

use App\Exceptions\MaxExtrasExceeded;
use App\Models\Console\ConsoleFactory;
use App\Models\Controller\ControllerFactory;
use App\Models\ElectronicItem;
use App\Models\Microwave\MicrowaveFactory;
use App\Models\Television\TelevisionFactory;
use App\Repositories\ElectronicItemsRepositoryFactory;
use Codeception\Test\Unit;

class ElectronicItemsRepositoryTest extends Unit
{

    private const INFINITE_MAX_EXTRAS = -1;
    private const FOUR_MAX_EXTRAS = 4;
    private const CAN_BE_SOLD_STANDALONE = true;
    private const CANNOT_BE_SOLD_STANDALONE = false;

    public function testGetItemsByType()
    {
        $electronicItems = $this->electronicItemsForScenario1();
        $electronicsRepository = $this->electronicItemsRepositoryFactory->createRepository($electronicItems);

        $televisions = $electronicsRepository->getItemsByType(ElectronicItem::ELECTRONIC_ITEM_TELEVISION);
        $this->assertCount(2, $televisions);

        $microwaves = $electronicsRepository->getItemsByType(ElectronicItem::ELECTRONIC_ITEM_MICROWAVE);
        $this->assertCount(1, $microwaves);

        $consoles = $electronicsRepository->getItemsByType(ElectronicItem::ELECTRONIC_ITEM_CONSOLE);
        $this->assertCount(1, $consoles);
    }

    public function testGetSortedItems()
    {
        $electronicItems = $this->electronicItemsForScenario1();
        $electronicsRepository = $this->electronicItemsRepositoryFactory->createRepository($electronicItems);

        $sortedItems = $electronicsRepository->getSortedItems(false);
        $sortedItemsKeys = array_keys($sortedItems);

        for ($i = 0; $i < count($sortedItemsKeys) - 1; $i++) {
            $this->assertLessThanOrEqual($sortedItemsKeys[$i + 1], $sortedItemsKeys[$i]);
        }

        $sortedItems = $electronicsRepository->getSortedItems(true);
        $sortedItemsKeys = array_keys($sortedItems);

        for ($i = 0; $i < count($sortedItemsKeys) - 1; $i++) {
            $this->assertGreaterThanOrEqual($sortedItemsKeys[$i + 1], $sortedItemsKeys[$i]);
        }
    }

    protected function setUp()
    : void
    {
        $this->electronicItemsRepositoryFactory = new ElectronicItemsRepositoryFactory();
        $this->consoleFactory = new ConsoleFactory(self::FOUR_MAX_EXTRAS, self::CAN_BE_SOLD_STANDALONE);
        $this->controllerFactory = new ControllerFactory(self::CANNOT_BE_SOLD_STANDALONE);
        $this->televisionFactory = new TelevisionFactory(self::INFINITE_MAX_EXTRAS, self::CAN_BE_SOLD_STANDALONE);
        $this->microwaveFactory = new MicrowaveFactory(self::CAN_BE_SOLD_STANDALONE);

        parent::setUp();
    }

    /**
     * This creates a list of electronic items to satisfy following criteria
     * 2 televisions with different prices
     * 1 television has 2 remote (wireless) controllers
     * 1 television has 1 remote (wireless) controller
     * 1 console with 2 remote (wireless) and 2 wired controllers
     * 1 microwave
     *
     * @return ElectronicItem[]
     * @throws MaxExtrasExceeded
     */
    private function electronicItemsForScenario1()
    : array
    {
        $televisionProperties1 = ['id' => 1, 'price' => 2000, 'name' => 'Some TV 1'];
        $televisionProperties2 = ['id' => 2, 'price' => 1500, 'name' => 'Some TV 1'];

        $microwaveProperties = ['id' => 1, 'price' => 500, 'name' => 'Standard Microwave'];
        $consoleProperties = ['id' => 1, 'price' => 3000, 'name' => 'Standard Console'];

        $wirelessControllerProperties = [
            'id'         => 1,
            'price'      => 200,
            'name'       => 'Standard Wireless Controller',
            'isWireless' =>
                true
        ];
        $wiredControllerProperties = [
            'id'         => 2,
            'price'      => 100,
            'name'       => 'Standard Wired Controller',
            'isWireless' =>
                false
        ];

        $wirelessController = $this->controllerFactory->createItemFromData($wirelessControllerProperties);
        $wiredController = $this->controllerFactory->createItemFromData($wiredControllerProperties);

        $television1 = $this->televisionFactory->createItemFromData($televisionProperties1);
        $television1->addController($wirelessController, 2);

        $television2 = $this->televisionFactory->createItemFromData($televisionProperties2);
        $television2->addController($wirelessController, 1);

        $console = $this->consoleFactory->createItemFromData($consoleProperties);
        $console->addController($wirelessController, 2);
        $console->addController($wiredController, 2);

        $microwave = $this->microwaveFactory->createItemFromData($microwaveProperties);

        return [
            $television1,
            $television2,
            $console,
            $microwave
        ];
    }

    private ConsoleFactory $consoleFactory;
    private ControllerFactory $controllerFactory;
    private ElectronicItemsRepositoryFactory $electronicItemsRepositoryFactory;
    private TelevisionFactory $televisionFactory;
    private MicrowaveFactory $microwaveFactory;
}
